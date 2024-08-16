<?php

namespace App\Http\Controllers\BackEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class OnlineGatewayController extends Controller
{
  public function onlineGateways()
  {
    $gatewayInfo['paypal'] = OnlineGateway::where('keyword', 'paypal')->first();
    $gatewayInfo['stripe'] = OnlineGateway::where('keyword', 'stripe')->first();
    $gatewayInfo['paytm'] = OnlineGateway::where('keyword', 'paytm')->first();
    $gatewayInfo['instamojo'] = OnlineGateway::where('keyword', 'instamojo')->first();
    $gatewayInfo['paystack'] = OnlineGateway::where('keyword', 'paystack')->first();
    $gatewayInfo['flutterwave'] = OnlineGateway::where('keyword', 'flutterwave')->first();
    $gatewayInfo['mollie'] = OnlineGateway::where('keyword', 'mollie')->first();
    $gatewayInfo['razorpay'] = OnlineGateway::where('keyword', 'razorpay')->first();
    $gatewayInfo['mercadopago'] = OnlineGateway::where('keyword', 'mercadopago')->first();

    return view('backend.payment_gateways.online_gateways', $gatewayInfo);
  }

  public function updatePayPalInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'sandbox_status' => 'required',
      'client_id' => 'required',
      'client_secret' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->sandbox_status;
    $information['client_id'] = $request->client_id;
    $information['client_secret'] = $request->client_secret;

    $paypalInfo = OnlineGateway::where('keyword', 'paypal')->first();

    $paypalInfo->update($request->except('information') + [
      'information' => json_encode($information)
    ]);

    session()->flash('success', 'PayPal\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateStripeInfo(Request $request)
  {
    $stripe = OnlineGateway::where('keyword', 'stripe')->first();
    $stripe->status = $request->status;

    $information = [];
    $information['key'] = $request->key;
    $information['secret'] = $request->secret;

    $stripe->information = json_encode($information);

    $stripe->save();

    session()->flash('success', "Stripe informations updated successfully!");

    return back();
  }

  public function updateInstamojoInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'sandbox_status' => 'required',
      'instamojo_key' => 'required',
      'instamojo_token' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->sandbox_status;
    $information['instamojo_key'] = $request->instamojo_key;
    $information['instamojo_token'] = $request->instamojo_token;

    $instamojoInfo = OnlineGateway::where('keyword', 'instamojo')->first();

    $instamojoInfo->update($request->except('information') + [
      'information' => json_encode($information)
    ]);

    session()->flash('success', 'Instamojo\'s information updated successfully!');

    return redirect()->back();
  }

  public function updatePaystackInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'paystack_key' => 'required',
      'paystack_email' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['paystack_key'] = $request->paystack_key;
    $information['paystack_email'] = $request->paystack_email;

    $paystackInfo = OnlineGateway::where('keyword', 'paystack')->first();

    $paystackInfo->update($request->except('information') + [
      'information' => json_encode($information)
    ]);

    session()->flash('success', 'Paystack\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateFlutterwaveInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'flutterwave_public_key' => 'required',
      'flutterwave_secret_key' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['flutterwave_public_key'] = $request->flutterwave_public_key;
    $information['flutterwave_secret_key'] = $request->flutterwave_secret_key;

    $flutterwaveInfo = OnlineGateway::where('keyword', 'flutterwave')->first();

    $flutterwaveInfo->update($request->except('information') + [
      'information' => json_encode($information)
    ]);

    session()->flash('success', 'Flutterwave\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateRazorpayInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'razorpay_key' => 'required',
      'razorpay_secret' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['razorpay_key'] = $request->razorpay_key;
    $information['razorpay_secret'] = $request->razorpay_secret;

    $razorpayInfo = OnlineGateway::where('keyword', 'razorpay')->first();

    $razorpayInfo->update($request->except('information') + [
      'information' => json_encode($information)
    ]);

    session()->flash('success', 'Razorpay\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateMercadoPagoInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'sandbox_status' => 'required',
      'mercadopago_token' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->sandbox_status;
    $information['mercadopago_token'] = $request->mercadopago_token;

    $mercadopagoInfo = OnlineGateway::where('keyword', 'mercadopago')->first();

    $mercadopagoInfo->update($request->except('information') + [
      'information' => json_encode($information)
    ]);

    session()->flash('success', 'MercadoPago\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateMollieInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'mollie_key' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['mollie_key'] = $request->mollie_key;

    $mollieInfo = OnlineGateway::where('keyword', 'mollie')->first();

    $mollieInfo->update($request->except('information') + [
      'information' => json_encode($information)
    ]);

    $array = ['MOLLIE_KEY' => $request->mollie_key];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    session()->flash('success', 'Mollie\'s information updated successfully!');

    return redirect()->back();
  }

  public function updatePaytmInfo(Request $request)
  {
    $rules = [
      'status' => 'required',
      'environment' => 'required',
      'merchant_key' => 'required',
      'merchant_mid' => 'required',
      'merchant_website' => 'required',
      'industry' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['environment'] = $request->environment;
    $information['merchant_key'] = $request->merchant_key;
    $information['merchant_mid'] = $request->merchant_mid;
    $information['merchant_website'] = $request->merchant_website;
    $information['industry'] = $request->industry;

    $paytmInfo = OnlineGateway::where('keyword', 'paytm')->first();

    $paytmInfo->update($request->except('information') + [
      'information' => json_encode($information)
    ]);

    $array = [
      'PAYTM_ENVIRONMENT' => $request->environment,
      'PAYTM_MERCHANT_ID' => $request->merchant_mid,
      'PAYTM_MERCHANT_KEY' => $request->merchant_key,
      'PAYTM_MERCHANT_WEBSITE' => $request->merchant_website,
      'PAYTM_INDUSTRY_TYPE' => $request->industry
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    session()->flash('success', 'Paytm\'s information updated successfully!');

    return redirect()->back();
  }
}
