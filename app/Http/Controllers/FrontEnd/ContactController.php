<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Config;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
  use MiscellaneousTrait;

  public function __construct()
  {
    $bs = DB::table('basic_settings')->select('google_recaptcha_site_key', 'google_recaptcha_secret_key')->first();
    Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
    Config::set('captcha.secret', $bs->google_recaptcha_secret_key);
  }

  public function contact()
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['pageHeading'] = MiscellaneousTrait::getPageHeading($language);

    return view('frontend.contact', $queryResult);
  }

  public function sendMail(Request $request)
  {
    $rules = [
      'full_name' => 'required',
      'email' => 'required|email:rfc,dns',
      'subject' => 'required',
      'message' => 'required'
    ];

    $bs = DB::table('basic_settings')->first();

    if ($bs->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }
    $messages = [
      'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
      'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $from = $request->email;
    $name = $request->full_name;
    $to = $bs->to_mail;
    $subject = $request->subject;

    $message = '<p>Message : ' . $request->message . '</p> <p><strong>Enquirer Name: </strong>' . $name . '<br/><strong>Enquirer Mail: </strong>' . $request->email . '</p>';

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    if ($bs->smtp_status == 1) {

      $mail->isSMTP();
      $mail->Host       = $bs->smtp_host;
      $mail->SMTPAuth   = true;
      $mail->Username   = $bs->smtp_username;
      $mail->Password   = $bs->smtp_password;

      if ($bs->encryption == 'TLS') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      }

      $mail->Port       = $bs->smtp_port;
    }

    try {
      $mail->setFrom($bs->from_mail, $bs->from_name);
      $mail->addAddress($to);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $message;

      $mail->send();

      Session::flash('success', 'Mail has been sent.');
    } catch (Exception $e) {
      Session::flash('error', 'Mail could not be sent!');
    }

    return redirect()->back();

    return redirect()->back();
  }
}
