<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\BasicSettings\MailTemplate;
use App\Models\PackageManagement\PackageBooking;
use App\Models\RoomManagement\RoomBooking;
use App\Models\SupportTicket;
use App\Models\SupportTicketStatus;
use App\Models\User;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use File;

class UserController extends Controller
{
  use MiscellaneousTrait;

  protected $breadcrumb;

  public function __construct()
  {
    $bs = DB::table('basic_settings')
      ->select('google_recaptcha_site_key', 'google_recaptcha_secret_key', 'facebook_app_id', 'facebook_app_secret', 'google_client_id', 'google_client_secret')
      ->first();

    Config::set('captcha.sitekey', $bs->google_recaptcha_site_key);
    Config::set('captcha.secret', $bs->google_recaptcha_secret_key);

    Config::set('services.facebook.client_id', $bs->facebook_app_id);
    Config::set('services.facebook.client_secret', $bs->facebook_app_secret);
    Config::set('services.facebook.redirect', url('login/facebook/callback'));

    Config::set('services.google.client_id', $bs->google_client_id);
    Config::set('services.google.client_secret', $bs->google_client_secret);
    Config::set('services.google.redirect', url('login/google/callback'));

    $this->breadcrumb = MiscellaneousTrait::getBreadcrumb();
  }

  public function login(Request $request)
  {
    // store previous url in session to redirect that path after login
    if (
      !empty(request()->input('redirectPath')) &&
      (request()->input('redirectPath') == 'room_details')
    ) {
      $url = url()->previous();
    } else if (
      !empty(request()->input('redirectPath')) &&
      (request()->input('redirectPath') == 'package_details')
    ) {
      $url = url()->previous();
    }

    if (isset($url)) {
      session()->put('redirectTo', $url);
    }

    $basic = DB::table('basic_settings')
      ->select('facebook_login_status', 'google_login_status')
      ->first();

    return view('frontend.user.login', ['breadcrumbInfo' => $this->breadcrumb, 'basicInfo' => $basic]);
  }

  public function loginSubmit(Request $request)
  {
    // at first, get the url from session which will be redirect after login
    if (session()->has('redirectTo')) {
      $redirectURL = session()->get('redirectTo');
    } else {
      $redirectURL = null;
    }

    $rules = [
      'username' => 'required',
      'password' => 'required'
    ];

    $bs = DB::table('basic_settings')->select('google_recaptcha_status')->first();

    if ($bs->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }
    $messages = [
      'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
      'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    // get the email and password which has provided by the user
    $credentials = $request->only('username', 'password');

    // login attempt
    if (Auth::guard('web')->attempt($credentials)) {
      // otherwise, redirect auth user to next url
      if ($redirectURL == null) {
        return redirect()->route('user.dashboard');
      } else {
        // before, redirect to next url forget the session value
        session()->forget('redirectTo');

        return redirect($redirectURL);
      }
    } else {
      session()->flash('error', 'The provided credentials do not match our records!');

      return redirect()->back();
    }
  }

  public function forgetPassword()
  {
    return view('frontend.user.forget_password', ['breadcrumbInfo' => $this->breadcrumb]);
  }

  public function sendMail(Request $request)
  {
    $rules = [
      'email' => [
        'required',
        'email:rfc,dns',
        new MatchEmailRule('user')
      ]
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }


    $user = User::where('email', $request->email)->first();

    // first, get the mail template information from db
    $mailTemplate = MailTemplate::where('mail_type', 'reset_password')->first();
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second, send a password reset link to user via email
    $info = DB::table('basic_settings')->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->where('uniqid', 12345)
      ->first();

    $name = $user->first_name . ' ' . $user->last_name;

    $code = Str::random(16);
    $user->password_code = $code;
    $user->save();

    $link = '<a href=' . url("user/reset_password/" . $code) . '>Click Here</a>';

    // replace template's curly-brace string with actual data
    $mailBody = str_replace('{customer_name}', $name, $mailBody);
    $mailBody = str_replace('{click_here}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

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
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($request->email);

      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body = $mailBody;

      $mail->send();

      session()->flash('success', 'A mail has been sent to your email address.');
    } catch (Exception $e) {
      session()->flash('error', 'Mail could not be sent!');
    }

    // store user email in session to use it later
    session()->put('userEmail', $user->email);

    return redirect()->back();
  }

  public function resetPassword($code)
  {
    return view('frontend.user.reset_password', ['breadcrumbInfo' => $this->breadcrumb]);
  }

  public function resetPasswordSubmit(Request $request)
  {

    $rules = [
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => 'Password confirmation failed.',
      'new_password_confirmation.required' => 'The confirm new password field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    $user = User::where('password_code', $request->password_code)->first();
    if ($user) {
      $user->update([
        'password' => Hash::make($request->new_password),
        'password_code' => NULL
      ]);
      session()->flash('success', 'Password updated successfully.');
      return redirect()->route('user.login');
    } else {
      Session::flash('error', 'Invalid Request');
      return redirect()->back();
    }
  }

  public function signup()
  {
    return view('frontend.user.signup', ['breadcrumbInfo' => $this->breadcrumb]);
  }

  public function signupSubmit(Request $request)
  {
    $rules = [
      'username' => 'required|unique:users|max:255',
      'email' => 'required|email:rfc,dns|unique:users|max:255',
      'password' => 'required|confirmed',
      'password_confirmation' => 'required'
    ];

    $message = [
      'password_confirmation.required' => 'The confirm password field is required.',
      'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
      'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.'
    ];

    $bs = DB::table('basic_settings')->select('google_recaptcha_status')->first();

    if ($bs->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = new User();
    $user->username = $request->username;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);

    // first, generate a random string
    $randStr = Str::random(20);

    // second, generate a token
    $token = md5($randStr . $request->username . $request->email);

    $user->verification_token = $token;
    $user->save();

    // send a mail to user for verify his/her email address
    $this->sendVerificationEmail($request, $token);

    return redirect()->back();
  }

  public function sendVerificationEmail(Request $request, $token)
  {
    // first get the mail template information from db
    $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second get the website title & mail's smtp information from db
    $info = DB::table('basic_settings')->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->where('uniqid', 12345)
      ->first();

    $link = '<a href=' . url("user/signup_verify/" . $token) . '>Click Here</a>';

    // replace template's curly-brace string with actual data
    $mailBody = str_replace('{username}', $request->username, $mailBody);
    $mailBody = str_replace('{verification_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

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
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($request->email);

      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body = $mailBody;

      $mail->send();

      session()->flash('success', 'A verification mail has been sent to your email address.');
    } catch (Exception $e) {
      session()->flash('error', 'Mail could not be sent!');
    }

    return;
  }

  public function signupVerify(Request $request, $token)
  {
    $user = User::where('verification_token', $token)->first();

    if (!is_null($user)) {
      $user->update([
        'email_verified_at' => date('Y-m-d H:i:s'),
        'email_verified' => 1
      ]);

      session()->flash('success', 'Your email has verified.');

      // after email verification authenticate this user
      Auth::guard('web')->login($user);

      // then redirect to user dashboard
      return redirect()->route('user.dashboard');
    } else {
      session()->flash('error', 'Could not verify your email!');

      return redirect()->route('user.signup');
    }
  }

  public function redirectToDashboard()
  {
    $queryResult['breadcrumbInfo'] = $this->breadcrumb;

    $authUser = Auth::guard('web')->user();
    $support_ticket_status = SupportTicketStatus::first();
    $queryResult['totalRoomBooking'] = RoomBooking::where('user_id', $authUser->id)->count();

    $queryResult['totalPackageBooking'] = PackageBooking::where('user_id', $authUser->id)->count();

    $queryResult['support_ticket_status'] = $support_ticket_status;

    if ($support_ticket_status->support_ticket_status == 'active') {
      $queryResult['totalSupportTickets'] = SupportTicket::where([['user_id', $authUser->id], ['user_type', 'user']])->count();
    }

    return view('frontend.user.dashboard', $queryResult);
  }

  public function roomBookings()
  {
    $queryResult['breadcrumbInfo'] = $this->breadcrumb;

    $authUser = Auth::guard('web')->user();

    $queryResult['roomBookingInfos'] = RoomBooking::where('user_id', $authUser->id)->orderBy('id', 'DESC')->get();

    $queryResult['langInfo'] = MiscellaneousTrait::getLanguage();

    return view('frontend.user.room_bookings', $queryResult);
  }

  public function roomBookingDetails($id)
  {
    $queryResult['breadcrumbInfo'] = $this->breadcrumb;

    $roomBooking = RoomBooking::where('id', $id)->firstOrFail();

    if ($roomBooking->user_id != Auth::guard('web')->user()->id) {
      return redirect()->route('user.dashboard');
    }

    $queryResult['details'] = $roomBooking;

    $queryResult['userInfo'] = $roomBooking->roomBookedByUser()->firstOrFail();

    return view('frontend.user.room_booking_details', $queryResult);
  }

  public function packageBookings()
  {
    $queryResult['breadcrumbInfo'] = $this->breadcrumb;

    $authUser = Auth::guard('web')->user();

    $queryResult['packageBookingInfos'] = PackageBooking::where('user_id', $authUser->id)->orderBy('id', 'DESC')->get();

    $queryResult['langInfo'] = MiscellaneousTrait::getLanguage();

    return view('frontend.user.package_bookings', $queryResult);
  }

  public function packageBookingDetails($id)
  {
    $queryResult['breadcrumbInfo'] = $this->breadcrumb;

    $packageBooking = PackageBooking::where('id', $id)->firstOrFail();
    if ($packageBooking->user_id != Auth::guard('web')->user()->id) {
      return redirect()->route('user.dashboard');
    }
    $queryResult['details'] = $packageBooking;

    $queryResult['userInfo'] = $packageBooking->packageBookedByUser()->firstOrFail();

    return view('frontend.user.package_booking_details', $queryResult);
  }

  public function editProfile()
  {
    $queryResult['breadcrumbInfo'] = $this->breadcrumb;

    $queryResult['userInfo'] = Auth::guard('web')->user();

    return view('frontend.user.edit_profile', $queryResult);
  }

  public function updateProfile(UserProfileUpdateRequest $request)
  {
    if ($request->hasFile('user_image')) {
      $profilePhoto = $request->file('user_image');
      $photoName = time() . '.' . $profilePhoto->getClientOriginalExtension();
      $directory = public_path('assets/img/users/');

      if (!file_exists($directory)) {
        @mkdir($directory, 0775, true);
      }

      $profilePhoto->move($directory, $photoName);
    }

    $authUser = Auth::guard('web')->user();

    // first, delete the previous profile photo of user
    if ($request->exists('user_image')) {
      if (
        !is_null($authUser->image) &&
        file_exists(public_path('assets/img/users/') . $authUser->image)
      ) {
        @unlink(public_path('assets/img/users/') . $authUser->image);
      }
    }

    $authUser->update($request->except('image') + [
      'image' => $request->exists('user_image') ? $photoName : $authUser->image
    ]);

    session()->flash('success', 'Your profile updated successfully.');

    return redirect()->back();
  }

  public function changePassword()
  {
    return view('frontend.user.change_password', ['breadcrumbInfo' => $this->breadcrumb]);
  }

  public function updatePassword(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('user')
      ],
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => 'Password confirmation failed.',
      'new_password_confirmation.required' => 'The confirm new password field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    $user = Auth::guard('web')->user();

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    session()->flash('success', 'Password updated successfully.');

    return redirect()->back();
  }

  public function logoutSubmit()
  {
    Auth::guard('web')->logout();
    Session::forget('secret_login');
    return redirect()->route('user.login');
  }

  public function redirectToFacebook()
  {
    return Socialite::driver('facebook')->redirect();
  }

  public function handleFacebookCallback()
  {
    return $this->authUserViaProvider('facebook');
  }

  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {
    return $this->authUserViaProvider('google');
  }

  public function authUserViaProvider($provider)
  {
    if (Session::has('redirectTo')) {
      $redirectUrl = Session::get('redirectTo');

      Session::forget('redirectTo');
    } else {
      $redirectUrl = route('user.dashboard');
    }

    $user = Socialite::driver($provider)->user();

    //get and insert image
    @mkdir(public_path('assets/img/users/'), 0775, true);
    $avatar = $user->getAvatar();
    $fileContents = file_get_contents($avatar);

    $avatarName = $user->getId() . '.jpg';
    $path = public_path('assets/img/users/');
    file_put_contents($path . $avatarName, $fileContents);

    if ($provider == 'facebook') {
      $user = json_decode(json_encode($user), true);
    } elseif ($provider == 'google') {
      $user = json_decode(json_encode($user), true)['user'];
    }

    if ($provider == 'facebook') {
      $fname = $user['name'];
    } elseif ($provider == 'google') {
      $fname = $user['given_name'];
      $lname = $user['family_name'];
    }


    $email = $user['email'];
    $provider_id = $user['id'];

    // retrieve user via the email
    $user = User::where('email', $email)->first();

    // if doesn't exist, store the new user's info (email, name, avatar, provider_name, provider_id)
    if (empty($user)) {
      $user = new User;
      $user->email = $email;
      $user->first_name = $fname;

      if ($provider == 'google') {
        $user->last_name = $lname;
      }

      $user->image = $avatarName;
      $user->username = $provider_id;
      $user->provider = $provider;
      $user->provider_id = $provider_id;
      $user->status = 1;
      $user->email_verified = 1;
      $user->save();
    }

    // authenticate the user
    Auth::login($user);

    // if user is banned
    if ($user->status == 0) {
      Auth::guard('web')->logout();
    }

    // if logged in successfully
    return redirect($redirectUrl);
  }
}
