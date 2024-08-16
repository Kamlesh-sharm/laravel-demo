<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Earning;
use App\Models\PackageManagement\Package;
use App\Models\PackageManagement\PackageBooking;
use App\Models\RoomManagement\Room;
use App\Models\RoomManagement\RoomBooking;
use App\Models\Transaction;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class AdminController extends Controller
{
  public function login()
  {
    return view('backend.admin.login');
  }

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

    if (Auth::guard('admin')->attempt([
      'username' => $request->username,
      'password' => $request->password
    ])) {
      return redirect()->route('admin.dashboard');
    } else {
      return redirect()->back()->with('alert', 'Username or Password does not match!');
    }
  }

  public function forgetPassword()
  {
    return view('backend.admin.forget_password');
  }

  public function sendMail(Request $request)
  {
    $rules = [
      'email' => [
        'required',
        'email:rfc,dns',
        new MatchEmailRule('admin')
      ]
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }

    // create a new password and store it in db
    $newPassword = uniqid();

    $admin = Admin::where('email', $request->email)->firstOrFail();

    $admin->update([
      'password' => Hash::make($newPassword)
    ]);

    // send newly created password to admin via email
    $info = DB::table('basic_settings')->select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->where('uniqid', 12345)
      ->first();

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
      $mail->Subject = 'Reset Password';
      $mail->Body = 'Hello ' . $admin->first_name . ',<br/><br/>Your password has been reset. Your new password is: <strong>' . $newPassword . '</strong><br/>Now, you can login with your new password. You can change your password from your Dashboard.<br/><br/>Thank you.';

      $mail->send();

      session()->flash('success', 'A mail has been sent to your email address with new password.');
    } catch (Exception $e) {
      session()->flash('warning', 'Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
    }

    return redirect()->back();
  }

  public function redirectToDashboard()
  {
    $data['pbookings'] = PackageBooking::orderBy('id', 'desc')->limit(10)->get();
    $data['rbookings'] = RoomBooking::orderBy('id', 'desc')->limit(10)->get();

    $data['roomsCount'] = Room::count();
    $data['allRbCount'] = RoomBooking::count();
    $data['paidRbCount'] = RoomBooking::where('payment_status', 1)->count();
    $data['packagesCount'] = Package::count();
    $data['allPbCount'] = PackageBooking::count();
    $data['paidPbCount'] = PackageBooking::where('payment_status', 1)->count();


    $data['earning'] = Earning::first();

    $data['transcation_count'] = Transaction::get()->count();

    $monthWiseTotalBookings = DB::table('room_bookings')
      ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total_booking'))
      ->where('payment_status', 1)
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->get();

    $monthWiseTotalIncomes = DB::table('room_bookings')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('payment_status', 1)
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

      // get all 12 months's equipment booking
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

      // get all 12 months's income of equipment booking
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
    }

    $data['months'] = $months;
    $data['bookings'] = $bookings;
    $data['incomes'] = $incomes;

    return view('backend.admin.dashboard', $data);
  }

  public function editProfile()
  {
    $adminInfo = Auth::guard('admin')->user();

    return view('backend.admin.edit_profile', compact('adminInfo'));
  }

  public function updateProfile(Request $request)
  {
    $authAdminId = Auth::guard('admin')->user()->id;

    $rules = [
      'username' => [
        'required',
        Rule::unique('admins')->ignore($authAdminId)
      ],
      'email' => [
        'required',
        'email:rfc,dns',
        Rule::unique('admins')->ignore($authAdminId)
      ],
      'first_name' => 'required',
      'last_name' => 'required'
    ];

    $imgURL = $request->image;

    if ($request->hasFile('image')) {
      $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
      $fileExtension = $request->file('image')->getClientOriginalExtension();

      $rules['image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
        if (!in_array($fileExtension, $allowedExtensions)) {
          $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
        }
      };
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    $admin = Auth::guard('admin')->user();
    if ($request->hasFile('image')) {
      $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
      $directory = public_path('assets/img/admins/');
      @mkdir($directory, 0775, true);
      $request->file('image')->move($directory, $filename);
      @unlink(public_path('assets/img/admins/') . $admin->image);
      $imgName = $filename;
    }

    $admin->update([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'image' => $request->hasFile('image') ? $imgName : $admin->image,
      'username' => $request->username,
      'email' => $request->email,
      'address' => $request->address,
      'details' => $request->details,
    ]);

    session()->flash('success', 'Profile updated successfully!');

    return redirect()->back();
  }

  public function changePassword()
  {
    return view('backend.admin.change_password');
  }

  public function updatePassword(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('admin')
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

    $admin = Auth::guard('admin')->user();

    $admin->update([
      'password' => Hash::make($request->new_password)
    ]);

    session()->flash('success', 'Password updated successfully!');

    return 'success';
  }

  public function logout(Request $request)
  {
    Auth::guard('admin')->logout();
    Session::forget('secret_login');

    return redirect()->route('admin.login');
  }

  public function changeTheme(Request $request)
  {
    return redirect()->back()->withCookie(cookie()->forever('admin-theme', $request->theme));
  }


  //transaction 
  public function transcation(Request $request)
  {
    $transcation_id = null;
    if ($request->filled('transcation_id')) {
      $transcation_id = $request->transcation_id;
    }

    $info['transcations'] = Transaction::when($transcation_id, function ($query) use ($transcation_id) {
      return $query->where('transcation_id', 'like', '%' . $transcation_id . '%');
    })->orderByDesc('id')->paginate(10);

    return view('backend.admin.transcation', $info);
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

  //monthly  income
  public function monthly_profit(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }
    $monthWiseTotalIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(commission) as total'))
      ->where('payment_status', 1)
      ->whereIn('transcation_type', [1, 5])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    $months = [];
    $incomes = [];
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's income of equipment booking
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
    }
    $information['months'] = $months;
    $information['incomes'] = $incomes;

    return view('backend.admin.profit', $information);
  }
  //monthly  earning
  public function monthly_earning(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }
    $monthWiseTotalIncomes = DB::table('transactions')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('payment_status', 1)
      ->whereIn('transcation_type', [1, 5])
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();


    $months = [];
    $incomes = [];
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('F');
      array_push($months, $monthName);

      // get all 12 months's income of equipment booking
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
    }
    $information['months'] = $months;
    $information['incomes'] = $incomes;

    return view('backend.admin.earning', $information);
  }
}
