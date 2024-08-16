<?php

namespace App\Http\Controllers\FrontEnd\Room;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Room\RoomBookingController;
use App\Models\Commission;
use App\Models\Earning;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\RoomManagement\Room;
use App\Models\RoomManagement\RoomBooking;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;

class FlutterwaveController extends Controller
{
  use MiscellaneousTrait;

  private $public_key, $secret_key;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('flutterwave')->first();
    $flutterwaveData = json_decode($data->information, true);

    $this->public_key = $flutterwaveData['flutterwave_public_key'];
    $this->secret_key = $flutterwaveData['flutterwave_secret_key'];
  }

  public function bookingProcess(Request $request)
  {
    $roomBooking = new RoomBookingController();

    // do calculation
    $calculatedData = $roomBooking->calculation($request);

    $available_currency = array('BIF', 'CAD', 'CDF', 'CVE', 'EUR', 'GBP', 'GHS', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'NGN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'USD', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD');

    $currencyInfo = MiscellaneousTrait::getCurrencyInfo();

    // checking whether the base currency is allowed or not
    if (!in_array($currencyInfo->base_currency_text, $available_currency)) {
      return redirect()->back()->with('error', 'Invalid currency for flutterwave payment.');
    }

    $information['subtotal'] = $calculatedData['subtotal'];
    $information['discount'] = $calculatedData['discount'];
    $information['total'] = $calculatedData['total'];
    $information['currency_symbol'] = $currencyInfo->base_currency_symbol;
    $information['currency_symbol_position'] = $currencyInfo->base_currency_symbol_position;
    $information['currency_text'] = $currencyInfo->base_currency_text;
    $information['currency_text_position'] = $currencyInfo->base_currency_text_position;
    $information['method'] = 'Flutterwave';
    $information['type'] = 'online';

    // store the room booking information in database
    $booking_details = $roomBooking->storeData($request, $information);

    $notify_url = route('room_booking.flutterwave.notify');

    // set curl
    $curl = curl_init();
    $uniqId = time();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'amount' => intval($calculatedData['total']),
        'customer_email' => $booking_details->customer_email,
        'currency' => $booking_details->currency_text,
        'txref' => $uniqId,
        'PBFPubKey' => $this->public_key,
        'redirect_url' => $notify_url,
        'payment_plan' => ''
      ]),
      CURLOPT_HTTPHEADER => [
        "content-type: application/json",
        "cache-control: no-cache"
      ],
    ));

    $response = curl_exec($curl);

    // close curl
    curl_close($curl);

    $transaction = json_decode($response, true);

    // put some data in session before redirect to flutterwave url
    session()->put('bookingId', $booking_details->id);   // db row number
    session()->put('orderNumber', $uniqId);

    if (!array_key_exists('data', $transaction) || !array_key_exists('link', $transaction['data'])) {
      return redirect()->back()->with('error', 'API returned error: ' . $transaction['message']);
    } else {
      return redirect($transaction['data']['link']);
    }
  }

  public function notify(Request $request)
  {
    // get the information from Session
    $bookingId = session()->get('bookingId');
    $orderNumber = session()->get('orderNumber');

    // get the information from the url
    $urlInfo = $request->all();

    if (isset($urlInfo['txref'])) {
      $ref = $orderNumber;

      $query = array(
        "SECKEY" => $this->secret_key,
        "txref" => $ref
      );

      $data_string = json_encode($query);

      $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

      $response = curl_exec($ch);

      curl_close($ch);

      $resp = json_decode($response, true);

      if ($resp['status'] == 'error') {
        return redirect()->route('room_booking.cancel');
      }

      if ($resp['status'] = "success") {
        $paymentStatus = $resp['data']['status'];
        $chargeResponsecode = $resp['data']['chargecode'];

        if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($paymentStatus == "successful")) {
          // update the payment status for room booking in database
          $bookingInfo = RoomBooking::where('id', $bookingId)->first();

          $bookingInfo->update(['payment_status' => 1]);

          $roomBooking = new RoomBookingController();

          // generate an invoice in pdf format
          $invoice = $roomBooking->generateInvoice($bookingInfo);

          $room = Room::where('id', $bookingInfo->room_id)->first();
          if (!empty($room)) {
            if ($room->vendor_id != NULL) {
              $vendor_id = $room->vendor_id;
            } else {
              $vendor_id = NULL;
            }
          } else {
            $vendor_id = NULL;
          }

          //calculate commission
          $percent = Commission::select('room_booking_commission')->first();

          $commission = (($bookingInfo->grand_total) * $percent->room_booking_commission) / 100;

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
            $vendor->amount = $vendor->amount + ($bookingInfo->grand_total - ($commission));
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
            'transcation_type' => 1,
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
          $roomBooking->sendMail($bookingInfo);

          // remove all session data
          session()->forget('bookingId');
          session()->forget('paymentId');

          return redirect()->route('room_booking.complete');
        }
      }

      return redirect()->route('room_booking.cancel');
    }

    return redirect()->route('room_booking.cancel');
  }
}
