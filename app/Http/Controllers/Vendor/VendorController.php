<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Language;
use App\Models\PackageManagement\Package;
use App\Models\PackageManagement\PackageBooking;
use App\Models\RoomManagement\Room;
use App\Models\RoomManagement\RoomBooking;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use App\Traits\MiscellaneousTrait;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Str;

class VendorController extends Controller
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


        $this->breadcrumb = MiscellaneousTrait::getBreadcrumb();
    }
    //signup
    public function signup()
    {
        return view('vendors.auth.register', ['breadcrumbInfo' => $this->breadcrumb]);
    }
    //create
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'username' => 'required|unique:vendors',
            'email' => 'required|email|unique:vendors',
            'password' => 'required|confirmed|min:6',
        ];

        $message = [
            'password_confirmation.required' => 'The confirm password field is required.',
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.'
        ];

        if ($request->username == 'admin') {
            Session::flash('username_error', 'You can not use admin as a username!');
            return redirect()->back();
        }
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval', 'google_recaptcha_status')->first();

        if ($setting->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $in = $request->all();


        if ($setting->vendor_email_verification == 1) {
            // first, get the mail template information from db
            $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();

            $mailSubject = $mailTemplate->mail_subject;
            $mailBody = $mailTemplate->mail_body;

            // second, send a password reset link to user via email
            $info = DB::table('basic_settings')
                ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
                ->first();

            $name = $request->name;
            $token =  $request->email;

            $link = '<a href=' . url("vendor/email/verify?token=" . $token) . '>Click Here</a>';

            $mailBody = str_replace('{username}', $request->name, $mailBody);
            $mailBody = str_replace('{verification_link}', $link, $mailBody);
            $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

            // initialize a new mail
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

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

                $mail = $mail->send();

                Session::flash('success', ' Verification mail has been sent your email address!');
            } catch (\Exception $e) {
                Session::flash('error', 'Mail could not be sent!');
                return redirect()->back();
            }

            $in['status'] = 0;
        } else {
            Session::flash('success', 'Sign up successfully completed.Please Login Now');
        }
        if ($setting->vendor_admin_approval == 1) {
            $in['status'] = 0;
        }

        if ($setting->vendor_admin_approval == 0 && $setting->vendor_email_verification == 0) {
            $in['status'] = 1;
        }

        $in['password'] = Hash::make($request->password);
        $vendor = Vendor::create($in);
        $languages = Language::get();
        foreach ($languages as $language) {
            $vendor_info = new VendorInfo();
            $vendor_info->language_id = $language->id;
            $vendor_info->vendor_id = $vendor->id;
            $vendor_info->name = $request->name;
            $vendor_info->save();
        }

        return redirect()->route('vendor.login');
    }

    //login
    public function login()
    {
        return view('vendors.auth.login', ['breadcrumbInfo' => $this->breadcrumb]);
    }

    //authenticate
    public function authentication(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if (
            Auth::guard('vendor')->attempt([
                'username' => $request->username,
                'password' => $request->password
            ])
        ) {
            $authAdmin = Auth::guard('vendor')->user();

            $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval')->first();

            // check whether the admin's account is active or not
            if ($setting->vendor_email_verification == 1 && $authAdmin->email_verified_at == NULL && $authAdmin->status == 0) {
                Session::flash('error', 'Please Verify Your Email Address!');

                // logout auth admin as condition not satisfied
                Auth::guard('vendor')->logout();
                Session::forget('secret_login');

                return redirect()->back();
            } elseif ($setting->vendor_email_verification == 0 && $setting->vendor_admin_approval == 1) {
                return redirect()->route('vendor.dashboard');
            } else {
                return redirect()->route('vendor.dashboard');
            }
        } else {
            return redirect()->back()->with('error', 'Oops, Username or password does not match!');
        }
    }
    //confirm_email'
    public function confirm_email()
    {
        $email = request()->input('token');
        $user = Vendor::where('email', $email)->first();
        $user->email_verified_at = now();
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval')->first();
        if ($setting->vendor_admin_approval != 1) {
            $user->status = 1;
        }

        $user->save();
        Auth::guard('vendor')->login($user);
        return redirect()->route('vendor.dashboard');
    }
    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        Session::forget('secret_login');
        return redirect()->route('vendor.login');
    }

    public function dashboard()
    {
        $information['transcations'] = Transaction::where('vendor_id', Auth::guard('vendor')->user()->id)->orderBy('id', 'desc')->get()->count();

        $information['totalRoom'] = Room::query()->where('vendor_id', Auth::guard('vendor')->user()->id)->count();
        $information['totalRoomBooking'] = RoomBooking::query()->where('vendor_id', Auth::guard('vendor')->user()->id)->count();

        $information['totalPackage'] = Package::query()->where('vendor_id', Auth::guard('vendor')->user()->id)->count();
        $information['totalPackageBooking'] = PackageBooking::query()->where('vendor_id', Auth::guard('vendor')->user()->id)->count();

        $monthWiseTotalBookings = DB::table('room_bookings')
            ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_booking'))
            ->where('payment_status', 1)
            ->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->groupBy('month')
            ->whereYear('created_at', '=', date('Y'))
            ->get();

        $monthWiseTotalIncomes = DB::table('room_bookings')
            ->select(DB::raw('month(created_at) as month'), DB::raw('sum(received_amount) as total'))
            ->where('payment_status', 1)
            ->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->groupBy('month')
            ->whereYear('created_at', '=', date('Y'))
            ->get();

        $months = [];
        $bookings = [];
        $incomes = [];

        for ($i = 1; $i <= 12; $i++) {
            // get all 12 months name
            $monthNum = $i;
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('M');
            array_push($months, $monthName);

            // get all 12 months's room booking
            $bookingFound = false;

            foreach ($monthWiseTotalBookings as $bookingInfo) {
                if ($bookingInfo->month == $i) {
                    $bookingFound = true;
                    array_push($bookings, $bookingInfo->total_booking);
                    break;
                }
            }

            if ($bookingFound == false) {
                array_push($bookings, 0);
            }

            // get all 12 months's income of room booking
            $incomeFound = false;

            foreach ($monthWiseTotalIncomes as $incomeInfo) {
                if ($incomeInfo->month == $i) {
                    $incomeFound = true;
                    array_push($incomes, round($incomeInfo->total, 2));
                    break;
                }
            }

            if ($incomeFound == false) {
                array_push($incomes, 0);
            }
        }


        //package bookings

        $monthWiseTotalPackageBookings = DB::table('package_bookings')
            ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_booking'))
            ->where('payment_status', 1)
            ->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->groupBy('month')
            ->whereYear('created_at', '=', date('Y'))
            ->get();

        $packageMonthWiseTotalIncomes = DB::table('package_bookings')
            ->select(DB::raw('month(created_at) as month'), DB::raw('sum(received_amount) as total'))
            ->where('payment_status', 1)
            ->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->groupBy('month')
            ->whereYear('created_at', '=', date('Y'))
            ->get();

        $p_months = [];
        $p_bookings = [];
        $p_incomes = [];
        for ($i = 1; $i <= 12; $i++) {
            // get all 12 p_months name
            $monthNum = $i;
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('M');
            array_push($p_months, $monthName);

            // get all 12 p_months's room booking
            $bookingFound = false;

            foreach ($monthWiseTotalPackageBookings as $bookingInfo) {
                if ($bookingInfo->month == $i) {
                    $bookingFound = true;
                    array_push($p_bookings, $bookingInfo->total_booking);
                    break;
                }
            }

            if ($bookingFound == false) {
                array_push($p_bookings, 0);
            }

            // get all 12 p_months's income of room booking
            $incomeFound = false;

            foreach ($packageMonthWiseTotalIncomes as $incomeInfo) {
                if ($incomeInfo->month == $i) {
                    $incomeFound = true;
                    array_push($p_incomes, round($incomeInfo->total, 2));
                    break;
                }
            }

            if ($incomeFound == false) {
                array_push($p_incomes, 0);
            }
        }


        $information['months'] = $months;
        $information['bookings'] = $bookings;
        $information['p_bookings'] = $p_bookings;
        $information['incomes'] = $incomes;
        $information['p_incomes'] = $p_incomes;


        $information['admin_setting'] = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval', 'admin_approval_notice')->first();

        return view('vendors.index', $information);
    }

    //change_password
    public function change_password()
    {
        return view('vendors.auth.change-password');
    }

    //update_password
    public function updated_password(Request $request)
    {
        $rules = [
            'current_password' => [
                'required',
                new MatchOldPasswordRule('vendor')

            ],
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $vendor = Auth::guard('vendor')->user();

        $vendor->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', 'Password updated successfully!');

        return 'success';
    }

    //edit_profile
    public function edit_profile()
    {
        $information['languages'] = Language::get();
        $information['vendor'] = Auth::guard('vendor')->user();
        return view('vendors.auth.edit-profile', $information);
    }
    //update_profile
    public function update_profile(Request $request)
    {
        $rules = [
            'username' => [
                'required',
                Rule::unique('vendors', 'username')->ignore(Auth::guard('vendor')->user()->id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore(Auth::guard('vendor')->user()->id)
            ]
        ];

        if ($request->hasFile('photo')) {
            $rules['photo'] = 'mimes:png,jpeg,jpg|dimensions:min_width=80,max_width=80,min_width=80,min_height=80';
        }

        $languages = Language::get();
        $message = [];
        foreach ($languages as $language) {
            $rules[$language->code . '_name'] = 'required';
            $message[$language->code . '_name.required'] = 'The Name feild is required.';
        }

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $in = $request->all();
        $vendor = Vendor::where('id', Auth::guard('vendor')->user()->id)->first();
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);

            @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
            $in['photo'] = $fileName;
        }

        if ($request->show_email_addresss) {
            $in['show_email_addresss'] = 1;
        } else {
            $in['show_email_addresss'] = 0;
        }
        if ($request->show_phone_number) {
            $in['show_phone_number'] = 1;
        } else {
            $in['show_phone_number'] = 0;
        }
        if ($request->show_contact_form) {
            $in['show_contact_form'] = 1;
        } else {
            $in['show_contact_form'] = 0;
        }

        foreach ($languages as $language) {
            $vendorInfo = VendorInfo::where('vendor_id', Auth::guard('vendor')->user()->id)->where('language_id', $language->id)->first();
            if (!$vendorInfo) {
                $vendorInfo = new VendorInfo();
                $vendorInfo->language_id = $language->id;
                $vendorInfo->vendor_id = Auth::guard('vendor')->user()->id;
            }
            $vendorInfo->name = $request[$language->code . '_name'];
            $vendorInfo->country = $request[$language->code . '_country'];
            $vendorInfo->city = $request[$language->code . '_city'];
            $vendorInfo->state = $request[$language->code . '_state'];
            $vendorInfo->zip_code = $request[$language->code . '_zip_code'];
            $vendorInfo->address = $request[$language->code . '_address'];
            $vendorInfo->details = $request[$language->code . '_details'];
            $vendorInfo->save();
        }

        $vendor->update($in);
        Session::flash('success', 'Profile updated successfully!');

        return 'success';
    }

    public function changeTheme(Request $request)
    {
        Session::put('vendor_theme_version', $request->vendor_theme_version);
        return redirect()->back();
    }

    //transcation 
    public function transcation(Request $request)
    {
        $transaction_id = null;
        if ($request->filled('transaction_id')) {
            $transaction_id = $request->transaction_id;
        }
        $transcations = Transaction::where('vendor_id', Auth::guard('vendor')->user()->id)->orderBy('id', 'desc')
            ->when($transaction_id, function ($query) use ($transaction_id) {
                return $query->where('transcation_id', 'like', '%' . $transaction_id . '%');
            })
            ->paginate(10);
        return view('vendors.transcation', compact('transcations'));
    }

    //destroy
    public function destroy(Request $request)
    {
        $transcation = Transaction::where('id', $request->id)->first();
        $transcation->delete();
        Session::flash('success', 'Transaction Deleted successfully!');

        return back();
    }

    //destroy
    public function bulk_destroy(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $transcation = Transaction::where('id', $id)->first();
            $transcation->delete();
        }
        Session::flash('success', 'Transaction Deleted successfully!');

        return 'success';
    }


    //forget_passord
    public function forget_passord()
    {
        return view('vendors.auth.forget-password', ['breadcrumbInfo' => $this->breadcrumb]);
    }
    //forget_mail
    public function forget_mail(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns',
                new MatchEmailRule('vendor')
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Vendor::where('email', $request->email)->first();

        // first, get the mail template information from db
        $mailTemplate = MailTemplate::where('mail_type', 'reset_password')->first();
        $mailSubject = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // second, send a password reset link to user via email
        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $name = $user->name;
        $token =  Str::random(32);
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
        ]);

        $link = '<a href=' . url("vendor/reset-password?token=" . $token) . '>Click Here</a>';

        $mailBody = str_replace('{customer_name}', $name, $mailBody);
        $mailBody = str_replace('{click_here}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

        // initialize a new mail
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

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

            Session::flash('success', 'A mail has been sent to your email address.');
        } catch (\Exception $e) {
            Session::flash('error', 'Mail could not be sent!');
        }

        // store user email in session to use it later
        $request->session()->put('userEmail', $user->email);

        return redirect()->back();
    }
    //reset_password
    public function reset_password()
    {
        return view('vendors.auth.reset-password', ['breadcrumbInfo' => $this->breadcrumb]);
    }
    //update_password
    public function update_password(Request $request)
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

        $reset = DB::table('password_resets')->where('token', $request->token)->first();
        $email = $reset->email;

        $vendor = Vendor::where('email',  $email)->first();

        $vendor->update([
            'password' => Hash::make($request->new_password)
        ]);
        DB::table('password_resets')->where('token', $request->token)->delete();
        Session::flash('success', 'Reset Your Password Successfully Completed.Please Login Now');

        return redirect()->route('vendor.login');
    }

    public function methodSettings()
    {
        $data = Vendor::where('id', Auth::guard('vendor')->user()->id)->select('self_pickup_status', 'two_way_delivery_status')->first();

        return view('vendors.shipping-methods', ['data' => $data]);
    }

    public function updateMethodSettings(Request $request)
    {
        $rules = [
            'self_pickup_status' => 'required|numeric',
            'two_way_delivery_status' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $vendor = Vendor::where('id', Auth::guard('vendor')->user()->id)->first();
        $vendor->self_pickup_status = $request->self_pickup_status;
        $vendor->two_way_delivery_status = $request->two_way_delivery_status;
        $vendor->save();

        $request->session()->flash('success', 'Settings updated successfully!');

        return redirect()->back();
    }

    //monthly  income
    public function monthly_income(Request $request)
    {
        if ($request->filled('year')) {
            $date = $request->input('year');
        } else {
            $date = date('Y');
        }


        $monthWiseTotalIncomes = DB::table('transactions')->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
            ->whereIn('transcation_type', [1, 3, 5])
            ->where('payment_status', 1)
            ->groupBy('month')
            ->whereYear('created_at', '=', $date)
            ->get();

        $monthWiseTotalCommission = DB::table('transactions')->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->select(DB::raw('month(created_at) as month'), DB::raw('sum(commission) as total'))
            ->whereIn('transcation_type', [1, 5])
            ->groupBy('month')
            ->whereYear('created_at', '=', $date)
            ->get();

        $monthlyTotalExpences = DB::table('transactions')->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
            ->whereIn('transcation_type', [2, 4])
            ->groupBy('month')
            ->whereYear('created_at', '=', $date)
            ->get();
        $monthlyTotalRetuns = DB::table('transactions')->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
            ->where([['transcation_type', 2], ['payment_status', 2]])
            ->groupBy('month')
            ->whereYear('created_at', '=', $date)
            ->get();

        $months = [];
        $incomes = [];
        $commissions = [];
        $expenses = [];
        $returns = [];
        for ($i = 1; $i <= 12; $i++) {
            // get all 12 months name
            $monthNum = $i;
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('M');
            array_push($months, $monthName);

            // get all 12 months's income of room booking , package booking, balance add
            $incomeFound = false;
            foreach ($monthWiseTotalIncomes as $incomeInfo) {
                if ($incomeInfo->month == $i) {
                    $incomeFound = true;
                    array_push($incomes, $incomeInfo->total);
                    break;
                }
            }
            if ($incomeFound == false) {
                array_push($incomes, 0);
            }

            //get 12 month's expenses 
            $expensesFound = false;
            foreach ($monthlyTotalExpences as $expenseInfo) {
                if ($expenseInfo->month == $i) {
                    $expensesFound = true;
                    array_push($expenses, $expenseInfo->total);
                    break;
                }
            }
            if ($expensesFound == false) {
                array_push($expenses, 0);
            }

            //get 12 month's commissions
            $commissionFound = false;
            foreach ($monthWiseTotalCommission as $commissionInfo) {
                if ($commissionInfo->month == $i) {
                    $commissionFound = true;
                    array_push($commissions, $commissionInfo->total);
                    break;
                }
            }

            if ($commissionFound == false) {
                array_push($commissions, 0);
            }

            //get 12 month's returns
            $returnFound = false;
            foreach ($monthlyTotalRetuns as $monthlyTotalRetun) {
                if ($monthlyTotalRetun->month == $i) {
                    $returnFound = true;
                    array_push($returns, $monthlyTotalRetun->total);
                    break;
                }
            }

            if ($returnFound == false) {
                array_push($returns, 0);
            }
        }
        $information['months'] = $months;
        $information['incomes'] = $incomes;
        $information['commissions'] = $commissions;
        $information['expenses'] = $expenses;
        $information['returns'] = $returns;

        return view('vendors.income', $information);
    }
}
