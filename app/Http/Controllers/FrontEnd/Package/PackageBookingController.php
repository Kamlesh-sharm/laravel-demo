<?php

namespace App\Http\Controllers\FrontEnd\Package;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Package\FlutterwaveController;
use App\Http\Controllers\FrontEnd\Package\InstamojoController;
use App\Http\Controllers\FrontEnd\Package\MercadoPagoController;
use App\Http\Controllers\FrontEnd\Package\MollieController;
use App\Http\Controllers\FrontEnd\Package\OfflineController;
use App\Http\Controllers\FrontEnd\Package\PayPalController;
use App\Http\Controllers\FrontEnd\Package\PaystackController;
use App\Http\Controllers\FrontEnd\Package\PaytmController;
use App\Http\Controllers\FrontEnd\Package\RazorpayController;
use App\Http\Controllers\FrontEnd\Package\StripeController;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Commission;
use App\Models\PackageManagement\Coupon;
use App\Models\PackageManagement\Package;
use App\Models\PackageManagement\PackageBooking;
use App\Traits\MiscellaneousTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class PackageBookingController extends Controller
{
  use MiscellaneousTrait;

  public function makePackageBooking(Request $request)
  {
    $package = Package::where('id', $request->package_id)->first();
    $maxPersons = $package->max_persons;

    // validation starts
    $rules = [
      'customer_name' => 'required',
      'customer_phone' => 'required',
      'customer_email' => 'required|email:rfc,dns',
      'visitors' => [
        'required',
        'numeric',
        function ($attribute, $value, $fail) use ($maxPersons) {
          if (!is_null($maxPersons) && $value > $maxPersons) {
            $fail('Number of ' . $attribute . ' exceeded');
          }
        }
      ]
    ];

    if ($request->paymentType == 'stripe') {
      $rules['card_number'] = 'required';
      $rules['cvc_number'] = 'required';
      $rules['expiry_month'] = 'required';
      $rules['expiry_year'] = 'required';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }
    // validation ends

    // check whether user is logged in or not (start)
    $status = DB::table('basic_settings')->select('package_guest_checkout_status')
      ->where('uniqid', '=', 12345)
      ->first();

    if (($status->package_guest_checkout_status == 0) && (Auth::guard('web')->check() == false)) {
      return redirect()->route('user.login', ['redirectPath' => 'package_details']);
    }
    // check whether user is logged in or not (end)

    if ($request->paymentType == 'none') {
      session()->flash('error', 'Please select a payment method.');

      return redirect()->back()->withInput();
    } else if ($request->paymentType == 'paypal') {
      $paypal = new PayPalController();

      return $paypal->bookingProcess($request);
    } else if ($request->paymentType == 'stripe') {
      $stripe = new StripeController();

      return $stripe->bookingProcess($request);
    } else if ($request->paymentType == 'instamojo') {
      $instamojo = new InstamojoController();

      return $instamojo->bookingProcess($request);
    } else if ($request->paymentType == 'paystack') {
      $paystack = new PaystackController();

      return $paystack->bookingProcess($request);
    } else if ($request->paymentType == 'razorpay') {
      $razorpay = new RazorpayController();

      return $razorpay->bookingProcess($request);
    } else if ($request->paymentType == 'mollie') {
      $mollie = new MollieController();

      return $mollie->bookingProcess($request);
    } else if ($request->paymentType == 'paytm') {
      $paytm = new PaytmController();

      return $paytm->bookingProcess($request);
    } else if ($request->paymentType == 'mercadopago') {
      $mercadopago = new MercadoPagoController();

      return $mercadopago->bookingProcess($request);
    } else if ($request->paymentType == 'flutterwave') {
      $flutterwave = new FlutterwaveController();

      return $flutterwave->bookingProcess($request);
    } else {
      $offline = new OfflineController();

      return $offline->bookingProcess($request);
    }
  }

  public function calculation(Request $request)
  {
    $packageInfo = Package::where('id', $request->package_id)->first();

    if ($packageInfo->pricing_type == 'per-person') {
      $subtotal = floatval($packageInfo->package_price) * intval($request->visitors);
    } else {
      $subtotal = floatval($packageInfo->package_price);
    }

    if (session()->has('couponCode')) {
      $coupon_code = session()->get('couponCode');

      $coupon = Coupon::where('code', $coupon_code)->first();

      if (!is_null($coupon)) {
        $couponVal = floatval($coupon->value);

        if ($coupon->type == 'fixed') {
          $total = $subtotal - $couponVal;

          $calculatedData = array(
            'subtotal' => $subtotal,
            'discount' => $couponVal,
            'total' => $total
          );
        } else {
          $discount = $subtotal * ($couponVal / 100);
          $total = $subtotal - $discount;

          $calculatedData = array(
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total
          );
        }
      } else {
        $calculatedData = array(
          'subtotal' => $subtotal,
          'discount' => 0.00,
          'total' => $subtotal
        );
      }
    } else {
      $calculatedData = array(
        'subtotal' => $subtotal,
        'discount' => 0.00,
        'total' => $subtotal
      );
    }

    session()->forget('couponCode');

    return $calculatedData;
  }

  public function storeData(Request $request, $information)
  {
    $room = Package::where('id', $request->package_id)->first();
    if (!empty($room)) {
      if ($room->vendor_id != NULL) {
        $vendor_id = $room->vendor_id;
      } else {
        $vendor_id = NULL;
      }
    } else {
      $vendor_id = NULL;
    }
    $commission = Commission::select('package_booking_commission')->first();

    $booking_details = PackageBooking::create([
      'booking_number' => time(),
      'user_id' => Auth::guard('web')->check() == true ? Auth::guard('web')->user()->id : null,
      'customer_name' => $request->customer_name,
      'customer_email' => $request->customer_email,
      'customer_phone' => $request->customer_phone,
      'package_id' => $request->package_id,
      'vendor_id' => $vendor_id,
      'visitors' => $request->visitors,
      'subtotal' => $information['subtotal'],
      'discount' => $information['discount'],
      'grand_total' => $information['total'],
      'currency_symbol' => $information['currency_symbol'],
      'currency_symbol_position' => $information['currency_symbol_position'],
      'currency_text' => $information['currency_text'],
      'currency_text_position' => $information['currency_text_position'],
      'payment_method' => $information['method'],
      'gateway_type' => $information['type'],
      'attachment' => $request->hasFile('attachment') ? $information['attachment'] : null,
      'commission_percentage' => $commission->package_booking_commission
    ]);

    return $booking_details;
  }

  public function generateInvoice($bookingInfo)
  {
    $fileName = $bookingInfo->booking_number . '.pdf';
    $directory = public_path('assets/invoices/packages/');

    @mkdir($directory, 0775, true);

    $fileLocated = $directory . $fileName;

    Pdf::loadView('frontend.pdf.package_booking', compact('bookingInfo'))->save($fileLocated);

    return $fileName;
  }

  public function sendMail($bookingInfo)
  {
    // first get the mail template information from db
    $mailTemplate = MailTemplate::where('mail_type', 'package_booking')->first();
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = replaceBaseUrl($mailTemplate->mail_body, 'summernote');

    // second get the website title & mail's smtp information from db
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    // get the package name according to language
    $language = MiscellaneousTrait::getLanguage();

    $packageInfo = $bookingInfo->tourPackage()->first();

    $packageContentInfo = $packageInfo->packageContent
      ->where('language_id', $language->id)
      ->first();

    $packageName = $packageContentInfo->title;

    // get the package price and currency information
    $currencyInfo = MiscellaneousTrait::getCurrencyInfo();

    $price = $bookingInfo->grand_total;

    $packagePrice = ($currencyInfo->base_currency_text_position == 'left' ? $currencyInfo->base_currency_text . ' ' : '') . $price . ($currencyInfo->base_currency_text_position == 'right' ? ' ' . $currencyInfo->base_currency_text : '');

    // replace template's curly-brace string with actual data
    $mailBody = str_replace('{customer_name}', $bookingInfo->customer_name, $mailBody);
    $mailBody = str_replace('{booking_number}', $bookingInfo->booking_number, $mailBody);
    $mailBody = str_replace('{booking_date}', date_format($bookingInfo->created_at, 'F d, Y'), $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);
    $mailBody = str_replace('{package_name}', '<a href="' . route('package_details', [$packageContentInfo->package_id, $packageContentInfo->slug]) . '">' . $packageName . '</a>', $mailBody);
    $mailBody = str_replace('{package_price}', $packagePrice, $mailBody);
    $mailBody = str_replace('{number_of_visitors}', $bookingInfo->visitors, $mailBody);

    // initialize a new mail
    $mail = new PHPMailer(true);

    // if smtp status == 1, then set some value for PHPMailer
    if ($info->smtp_status == 1) {
      $mail->isSMTP();
      $mail->Host       = $info->smtp_host;
      $mail->SMTPAuth   = true;
      $mail->Username   = $info->smtp_username;
      $mail->Password   = $info->smtp_password;

      if ($info->encryption == 'TLS') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      }

      $mail->Port       = $info->smtp_port;
    }

    // finally add other informations and send the mail
    try {
      // Recipients
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($bookingInfo->customer_email);

      // Attachments (Invoice)
      $mail->addAttachment(public_path('assets/invoices/packages/') . $bookingInfo->invoice);

      // Content
      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body    = $mailBody;

      $mail->send();

      return;
    } catch (Exception $e) {
      return redirect('/packages')->with('error', 'Mail could not be sent!');
    }
  }

  public function complete()
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

    return view('frontend.partials.payment_success', $queryResult);
  }

  public function cancel()
  {
    return redirect('/packages')->with('error', 'Sorry, an error has occured!');
  }
}
