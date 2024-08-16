<?php

namespace App\Http\Controllers\FrontEnd\Package;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Earning;
use App\Models\PackageManagement\Package;
use App\Models\PackageManagement\PackageBooking;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Traits\MiscellaneousTrait;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class StripeController extends Controller
{
  public function __construct()
  {
    $stripe = OnlineGateway::whereKeyword('stripe')->first();
    $stripeConf = json_decode($stripe->information, true);

    Config::set('services.stripe.key', $stripeConf["key"]);
    Config::set('services.stripe.secret', $stripeConf["secret"]);
  }

  public function bookingProcess(Request $request)
  {
    $packageBooking = new PackageBookingController();

    // do calculation
    $calculatedData = $packageBooking->calculation($request);

    $title = 'Package Booking';

    $currencyInfo = MiscellaneousTrait::getCurrencyInfo();

    $information['subtotal'] = $calculatedData['subtotal'];
    $information['discount'] = $calculatedData['discount'];
    $information['total'] = $calculatedData['total'];
    $information['currency_symbol'] = $currencyInfo->base_currency_symbol;
    $information['currency_symbol_position'] = $currencyInfo->base_currency_symbol_position;
    $information['currency_text'] = $currencyInfo->base_currency_text;
    $information['currency_text_position'] = $currencyInfo->base_currency_text_position;
    $information['method'] = 'Stripe';
    $information['type'] = 'online';

    // store the package booking information in database
    $booking_details = $packageBooking->storeData($request, $information);

    // changing the currency before redirect to Stripe
    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = $currencyInfo->base_currency_rate;
      $convertedTotal = round(($calculatedData['total'] / $rate), 2);
    }

    $stripeTotal = $currencyInfo->base_currency_text === 'USD' ? $calculatedData['total'] : $convertedTotal;

    try {
      // initialize stripe
      $stripe = Stripe::make(Config::get('services.stripe.secret'));

      try {
        // generate token
        $token = $stripe->tokens()->create([
          'card' => [
            'number'    => $request['card_number'],
            'cvc'       => $request['cvc_number'],
            'exp_month' => $request['expiry_month'],
            'exp_year'  => $request['expiry_year']
          ]
        ]);

        // generate charge
        $charge = $stripe->charges()->create([
          'source' => $token['id'],
          'currency' => 'USD',
          'amount'   => $stripeTotal
        ]);

        if ($charge['status'] == 'succeeded') {
          // update the payment status for package booking in database
          $bookingInfo = PackageBooking::where('id', $booking_details->id)->first();

          $bookingInfo->update(['payment_status' => 1]);

          // generate an invoice in pdf format
          $invoice = $packageBooking->generateInvoice($bookingInfo);

          $package = Package::where('id', $bookingInfo->package_id)->first();
          if (!empty($package)) {
            if ($package->vendor_id != NULL) {
              $vendor_id = $package->vendor_id;
            } else {
              $vendor_id = NULL;
            }
          } else {
            $vendor_id = NULL;
          }

          //calculate commission
          $percent = Commission::select('package_booking_commission')->first();

          $commission = (($bookingInfo->grand_total) * $percent->package_booking_commission) / 100;

          //get vendor
          $vendor = Vendor::where('id', $vendor_id)->first();

          //add blance to admin revinue
          $earning = Earning::first();

          $earning->total_revenue = $earning->total_revenue + $bookingInfo->grand_total;
          if ($vendor) {
            $earning->total_earning = $earning->total_earning + $commission;
          } else {
            $earning->total_earning = $earning->total_earning + $bookingInfo->grand_total;
          }
          $earning->save();

          //store Balance  to vendor
          if ($vendor) {
            $pre_balance = $vendor->amount;
            $vendor->amount = $vendor->amount + ($bookingInfo->grand_total - ($commission + $bookingInfo->tax));
            $vendor->save();
            $after_balance = $vendor->amount;

            $received_amount = ($bookingInfo->grand_total - ($commission));

            // then, update the invoice field info in database
            $bookingInfo->update([
              'invoice' => $invoice,
              'comission' => $commission,
              'received_amount' => $received_amount,
            ]);
          } else {
            // then, update the invoice field info in database
            $bookingInfo->update([
              'invoice' => $invoice
            ]);
            $received_amount = $bookingInfo->grand_total;
            $after_balance = NULL;
            $pre_balance = NULL;
          }
          //calculate commission end

          $data = [
            'transcation_id' => time(),
            'booking_id' => $bookingInfo->id,
            'transcation_type' => 5,
            'user_id' => null,
            'vendor_id' => $vendor_id,
            'payment_status' => 1,
            'payment_method' => $bookingInfo->payment_method,
            'grand_total' => $bookingInfo->grand_total,
            'commission' => $bookingInfo->comission,
            'pre_balance' => $pre_balance,
            'after_balance' => $after_balance,
            'gateway_type' => $bookingInfo->gateway_type,
            'currency_symbol' => $bookingInfo->currency_symbol,
            'currency_symbol_position' => $bookingInfo->currency_symbol_position,
          ];
          store_transaction($data);

          // send a mail to the customer with an invoice
          $packageBooking->sendMail($bookingInfo);

          return redirect()->route('package_booking.complete');
        } else {
          return redirect()->route('package_booking.cancel');
        }
      } catch (CardErrorException $e) {
        session()->flash('error', $e->getMessage());

        return redirect()->route('package_booking.cancel');
      }
    } catch (UnauthorizedException $e) {
      session()->flash('error', $e->getMessage());

      return redirect()->route('package_booking.cancel');
    }
  }
}
