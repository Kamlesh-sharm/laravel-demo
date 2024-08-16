<?php

namespace App\Http\Controllers\FrontEnd\Package;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Package\PackageBookingController;
use App\Models\PackageManagement\PackageBooking;
use App\Models\PaymentGateway\OfflineGateway;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfflineController extends Controller
{
  use MiscellaneousTrait;

  public function bookingProcess(Request $request)
  {
    $offlineMethod = OfflineGateway::where('id', $request->paymentType)->first();

    // check whether attachment is required or not
    if ($offlineMethod->attachment_status == 1) {
      $rules = [
        'attachment' => 'required|mimes:jpg,jpeg,png'
      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    }

    // store attachment in local storage
    if ($request->hasFile('attachment')) {
      $img = $request->file('attachment');
      $img_name = time() . '.' . $img->getClientOriginalExtension();
      $directory = public_path('assets/img/attachments/packages/');

      if (!file_exists($directory)) {
        @mkdir($directory,0775, true);
      }

      $img->move($directory, $img_name);
    }

    $packageBooking = new PackageBookingController();

    // do calculation
    $calculatedData = $packageBooking->calculation($request);

    $currencyInfo = MiscellaneousTrait::getCurrencyInfo();

    $information['subtotal'] = $calculatedData['subtotal'];
    $information['discount'] = $calculatedData['discount'];
    $information['total'] = $calculatedData['total'];
    $information['currency_symbol'] = $currencyInfo->base_currency_symbol;
    $information['currency_symbol_position'] = $currencyInfo->base_currency_symbol_position;
    $information['currency_text'] = $currencyInfo->base_currency_text;
    $information['currency_text_position'] = $currencyInfo->base_currency_text_position;
    $information['method'] = $offlineMethod->name;
    $information['type'] = 'offline';
    $information['attachment'] = $request->hasFile('attachment') ? $img_name : null;

    // store the package booking information in database
    $booking_details = $packageBooking->storeData($request, $information);

    $bookingInfo = PackageBooking::where('id', $booking_details->id)->first();

    // generate an invoice in pdf format
    $invoice = $packageBooking->generateInvoice($bookingInfo);

    // update the invoice field information in database
    $bookingInfo->update(['invoice' => $invoice]);

    // send a mail to the customer with an invoice
    $packageBooking->sendMail($bookingInfo);

    return redirect()->route('package_booking.complete', ['type' => 'offline']);
  }
}
