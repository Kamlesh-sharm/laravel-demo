<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Language;
use App\Models\PackageManagement\Package;
use App\Models\RoomManagement\Room;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Traits\MiscellaneousTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Validation\Rule;

class VendorManagementController extends Controller
{
    use MiscellaneousTrait;
    public function settings()
    {

        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval', 'admin_approval_notice')->first();
        return view('backend.vendor.settings', compact('setting'));
    }
    //update_setting
    public function update_setting(Request $request)
    {
        if ($request->vendor_email_verification) {
            $vendor_email_verification = 1;
        } else {
            $vendor_email_verification = 0;
        }
        if ($request->vendor_admin_approval) {
            $vendor_admin_approval = 1;
        } else {
            $vendor_admin_approval = 0;
        }
        // finally, store the favicon into db
        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'vendor_email_verification' => $vendor_email_verification,
                'vendor_admin_approval' => $vendor_admin_approval,
                'admin_approval_notice' => $request->admin_approval_notice,
            ]
        );

        Session::flash('success', 'Update Settings Successfully!');
        return back();
    }


    public function index(Request $request)
    {
        $searchKey = null;

        if ($request->filled('info')) {
            $searchKey = $request['info'];
        }

        $vendors = Vendor::when($searchKey, function ($query, $searchKey) {
            return $query->where('username', 'like', '%' . $searchKey . '%')
                ->orWhere('email', 'like', '%' . $searchKey . '%');
        })
            ->orderBy('id', 'desc')
            ->paginate(10);


        return view('backend.vendor.index', compact('vendors'));
    }

    //add
    public function add()
    {
        $information['languages'] = Language::get();
        return view('backend.vendor.create', $information);
    }
    public function create(Request $request)
    {
        $rules = [

            'username' => [
                'required',
                Rule::unique('vendors', 'username')
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')
            ],
            'password' => 'required|confirmed|min:6',
            'photo' => 'required|height:80|width:80',
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
        $in['password'] = Hash::make($request->password);
        $in['status'] = 1;

        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);
            $in['photo'] = $fileName;
        }
        $in['email_verified_at'] = Carbon::now();

        $vendor = Vendor::create($in);

        foreach ($languages as $language) {
            $vendorInfo = new VendorInfo();
            $vendorInfo->language_id = $language->id;
            $vendorInfo->vendor_id = $vendor->id;
            $vendorInfo->name = $request[$language->code . '_name'];
            $vendorInfo->country = $request[$language->code . '_country'];
            $vendorInfo->city = $request[$language->code . '_city'];
            $vendorInfo->state = $request[$language->code . '_state'];
            $vendorInfo->zip_code = $request[$language->code . '_zip_code'];
            $vendorInfo->address = $request[$language->code . '_address'];
            $vendorInfo->details = $request[$language->code . '_details'];
            $vendorInfo->save();
        }
        Session::flash('success', 'Vendor Added Successfully!');
        $data = [];
        $data['username'] = $vendor->username;
        $data['email'] = $request->email;
        $data['password'] = $request->password;
        $this->sendMail($data);
        return 'success';
    }

    public function show($id)
    {

        $information['langs'] = Language::all();

        $language = Language::where('code', request()->input('language'))->firstOrFail();
        $information['language'] = $language;
        $vendor = Vendor::where('id', $id)->firstOrFail();
        $vendorInfo = VendorInfo::where('vendor_id', $id)->first();
        $information['vendor'] = $vendor;
        $information['vendorInfo'] = $vendorInfo;

        $information['langs'] = Language::all();

        $language_id = $language->id;

        $information['allRooms'] = Room::query()->where('vendor_id', $vendor->id)->join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
            ->join('room_categories', 'room_categories.id', '=', 'room_contents.room_category_id')
            ->where('room_contents.language_id', '=', $language->id)
            ->select('rooms.id', 'rooms.featured_img', 'rooms.quantity', 'room_contents.title', 'room_categories.name as categoryName', 'rooms.is_featured')
            ->orderByDesc('rooms.id')
            ->get();

        $information['packages'] = Package::where('vendor_id', $vendor->id)
            ->with([
                'package_content' => function ($q) use ($language_id) {
                    $q->where('language_id', $language_id);
                }
            ])
            ->orderBy('id', 'desc')
            ->get();


        $information['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

        return view('backend.vendor.details', $information);
    }
    public function updateAccountStatus(Request $request, $id)
    {

        $user = Vendor::where('id', $id)->first();
        if ($request->account_status == 1) {
            $user->update(['status' => 1]);
        } else {
            $user->update(['status' => 0]);
        }
        Session::flash('success', 'Account status updated successfully!');

        return redirect()->back();
    }

    public function updateEmailStatus(Request $request, $id)
    {
        $vendor = Vendor::where('id', $id)->first();
        if ($request->email_status == 1) {
            $vendor->update(['email_verified_at' => now()]);
        } else {
            $vendor->update(['email_verified_at' => NULL]);
        }
        Session::flash('success', 'Email status updated successfully!');

        return redirect()->back();
    }
    public function changePassword($id)
    {
        $userInfo = Vendor::where('id', $id)->firstOrFail();

        return view('backend.vendor.change-password', compact('userInfo'));
    }
    public function updatePassword(Request $request, $id)
    {
        $rules = [
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

        $user = Vendor::where('id', $id)->first();

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', 'Password updated successfully!');

        return 'success';
    }

    public function edit($id)
    {
        $languages = Language::get();
        $information['languages'] = $languages;
        $vendor = Vendor::where('id', $id)->firstOrFail();
        $information['vendor'] = $vendor;
        return view('backend.vendor.edit', $information);
    }

    //update
    public function update(Request $request, $id, Vendor $vendor)
    {
        $rules = [

            'username' => [
                'required',
                Rule::unique('vendors', 'username')->ignore($id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore($id)
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
        $vendor  = Vendor::where('id', $id)->first();
        $vendor_id = $vendor->id;
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

        foreach ($languages as $language) {
            $vendorInfo = VendorInfo::where('vendor_id', $vendor_id)->where('language_id', $language->id)->first();
            if (!$vendorInfo) {
                $vendorInfo = new VendorInfo();
                $vendorInfo->language_id = $language->id;
                $vendorInfo->vendor_id = $id;
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
        Session::flash('success', 'Vendor updated successfully!');

        return "success";
    }

    //update_vendor_balance
    public function update_vendor_balance(Request $request, $id)
    {
        $request->validate([
            'amount_status',
            'amount',
        ]);
        $currency_info = Basic::select('base_currency_symbol_position', 'base_currency_symbol')
            ->first();
        $vendor = Vendor::where('id', $id)->first();
        //add or subtract vendor balance
        if ($request->amount_status == 1) {
            //store data to transcation table
            $transaction = Transaction::create([
                'transcation_id' => time(),
                'booking_id' => NULL,
                'transcation_type' => 3,
                'user_id' => NULL,
                'vendor_id' => $vendor->id,
                'payment_status' => 1,
                'payment_method' => NULL,
                'grand_total' => $request->amount,
                'pre_balance' => $vendor->amount != 0 ? $vendor->amount : 0.00,
                'after_balance' => $vendor->amount + $request->amount,
                'gateway_type' => NULL,
                'currency_symbol' => $currency_info->base_currency_symbol,
                'currency_symbol_position' => $currency_info->base_currency_symbol_position,
            ]);

            $new_vendor_amount = $vendor->amount + $request->amount;
        } else {
            //store data to transcation table
            $transaction = Transaction::create([
                'transcation_id' => time(),
                'booking_id' => NULL,
                'transcation_type' => 4,
                'user_id' => NULL,
                'vendor_id' => $vendor->id,
                'payment_status' => 1,
                'payment_method' => NULL,
                'grand_total' => $request->amount,
                'pre_balance' => $vendor->amount != 0 ? $vendor->amount : 0.00,
                'after_balance' => $vendor->amount - $request->amount,
                'gateway_type' => NULL,
                'currency_symbol' => $currency_info->base_currency_symbol,
                'currency_symbol_position' => $currency_info->base_currency_symbol_position,
            ]);

            $new_vendor_amount = $vendor->amount - $request->amount;
        }

        //send mail
        if ($request->amount_status == 1) {
            $template_type = 'balance_add';

            $vendor_alert_msg = "Balance added to vendor account succefully.!";
        } else {
            $template_type = 'balance_subtract';
            $vendor_alert_msg = "Balance Subtract from vendor account succefully.!";
        }
        //mail sending
        // get the website title & mail's smtp information from db
        $info = Basic::select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'base_currency_symbol_position', 'base_currency_symbol')
            ->first();

        //preparing mail info
        // get the mail template info from db
        $mailTemplate = MailTemplate::query()->where('mail_type', '=', $template_type)->first();
        $mailData['subject'] = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // get the website title info from db
        $website_info = Basic::select('website_title')->first();

        // preparing dynamic data
        $vendorName = $vendor->username;
        $vendorEmail = $vendor->email;
        $vendor_amount = $new_vendor_amount;

        $websiteTitle = $website_info->website_title;

        // replacing with actual data
        $mailBody = str_replace('{transaction_id}', $transaction->transcation_id, $mailBody);
        $mailBody = str_replace('{username}', $vendorName, $mailBody);
        $mailBody = str_replace('{amount}', $info->base_currency_symbol . $request->amount, $mailBody);

        $mailBody = str_replace('{current_balance}', $info->base_currency_symbol . $vendor_amount, $mailBody);
        $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);

        $mailData['body'] = $mailBody;

        $mailData['recipient'] = $vendorEmail;
        //preparing mail info end

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

        // add other informations and send the mail
        try {
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($mailData['recipient']);

            $mail->isHTML(true);
            $mail->Subject = $mailData['subject'];
            $mail->Body = $mailData['body'];

            $mail->send();
            Session::flash('success', $vendor_alert_msg);
        } catch (Exception $e) {
            Session::flash('warning', 'Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        }
        //mail sending end

        $vendor->amount = $new_vendor_amount;
        $vendor->save();

        return "success";
    }

    public function destroy($id)
    {
        $vendor = Vendor::where('id', $id)->first();
        /*********************************************/
        #============delete vendor rooms==========

        $rooms = $vendor->rooms()->get();

        foreach ($rooms as $room) {

            if ($room->roomContent()->count() > 0) {
                $contents = $room->roomContent()->get();

                foreach ($contents as $content) {
                    $content->delete();
                }
            }

            if (!is_null($room->slider_imgs)) {
                $images = json_decode($room->slider_imgs);

                foreach ($images as $image) {
                    if (file_exists(public_path('assets/img/rooms/slider_images/') . $image)) {
                        @unlink(public_path('assets/img/rooms/slider_images/') . $image);
                    }
                }
            }

            if (!is_null($room->featured_img) && file_exists(public_path('assets/img/rooms/') . $room->featured_img)) {
                @unlink(public_path('assets/img/rooms/') . $room->featured_img);
            }
            //delete room bookings

            $bookings = $room->roomBooking()->get();
            foreach ($bookings as $booking) {
                // first, delete the attachment
                if (
                    !is_null($booking->attachment) &&
                    file_exists(public_path('assets/img/attachments/rooms/') . $booking->attachment)
                ) {
                    @unlink(public_path('assets/img/attachments/rooms/') . $booking->attachment);
                }

                // second, delete the invoice
                if (
                    !is_null($booking->invoice) &&
                    file_exists(public_path('assets/invoices/rooms/') . $booking->invoice)
                ) {
                    @unlink(public_path('assets/invoices/rooms/') . $booking->invoice);
                }

                // finally, delete the room booking record from db
                $booking->delete();
            }

            $room->delete();
        }

        //pacages
        $packages = $vendor->packages()->get();
        foreach ($packages as $package) {

            $locations = $package->packageLocationList()->get();
            foreach ($locations as $location) {
                $location->delete();
            }

            $plans = $package->packagePlanList()->get();
            foreach ($plans as $plan) {
                $plan->delete();
            }

            // first, delete all the contents of this package
            $contents = $package->packageContent()->get();

            foreach ($contents as $content) {
                $content->delete();
            }

            // second, delete all the slider images of this package
            if (!is_null($package->slider_imgs)) {
                $images = json_decode($package->slider_imgs);

                foreach ($images as $image) {
                    if (file_exists(public_path('assets/img/packages/slider_images/') . $image)) {
                        @unlink(public_path('assets/img/packages/slider_images/') . $image);
                    }
                }
            }

            // third, delete featured image of this package
            if (!is_null($package->featured_img) && file_exists(public_path('assets/img/packages/') . $package->featured_img)) {
                @unlink(public_path('assets/img/packages/') . $package->featured_img);
            }

            // finally, delete this package
            $package->delete();
        }

        //delete all support ticket
        $support_tickets = SupportTicket::where([['user_id', $vendor->id], ['user_type', 'vendor']])->get();

        if (count($support_tickets) > 0) {
            foreach ($support_tickets as $support_ticket) {
                //delete conversation 
                $messages = $support_ticket->messages()->get();
                foreach ($messages as $message) {
                    @unlink(public_path('assets/img/support-ticket/' . $message->file));
                    $message->delete();
                }
                @unlink(public_path('assets/img/support-ticket/') . $support_ticket->attachment);
                $support_ticket->delete();
            }
        }

        //withdraws
        $withdraws = $vendor->withdraws()->get();
        foreach ($withdraws as $withdraw) {
            $withdraw->delete();
        }
        /*********************************************/
        #====finally delete the vendor=======
        @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
        //vendor_infos
        $vendorInfos = VendorInfo::where('vendor_id', $vendor->id)->get();
        foreach ($vendorInfos as $vendorInfo) {
            $vendorInfo->delete();
        }
        $vendor->delete();

        return redirect()->back()->with('success', 'Vendor info deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $vendor = Vendor::where('id', $id)->first();
            $rooms = $vendor->rooms()->get();

            foreach ($rooms as $room) {

                if ($room->roomContent()->count() > 0) {
                    $contents = $room->roomContent()->get();

                    foreach ($contents as $content) {
                        $content->delete();
                    }
                }

                if (!is_null($room->slider_imgs)) {
                    $images = json_decode($room->slider_imgs);

                    foreach ($images as $image) {
                        if (file_exists(public_path('assets/img/rooms/slider_images/') . $image)) {
                            @unlink(public_path('assets/img/rooms/slider_images/') . $image);
                        }
                    }
                }

                if (!is_null($room->featured_img) && file_exists(public_path('assets/img/rooms/') . $room->featured_img)) {
                    @unlink(public_path('assets/img/rooms/') . $room->featured_img);
                }
                //delete room bookings

                $bookings = $room->roomBooking()->get();
                foreach ($bookings as $booking) {
                    // first, delete the attachment
                    if (
                        !is_null($booking->attachment) &&
                        file_exists(public_path('assets/img/attachments/rooms/') . $booking->attachment)
                    ) {
                        @unlink(public_path('assets/img/attachments/rooms/') . $booking->attachment);
                    }

                    // second, delete the invoice
                    if (
                        !is_null($booking->invoice) &&
                        file_exists(public_path('assets/invoices/rooms/') . $booking->invoice)
                    ) {
                        @unlink(public_path('assets/invoices/rooms/') . $booking->invoice);
                    }

                    // finally, delete the room booking record from db
                    $booking->delete();
                }

                $room->delete();
            }

            //pacages
            $packages = $vendor->packages()->get();
            foreach ($packages as $package) {

                $locations = $package->packageLocationList()->get();
                foreach ($locations as $location) {
                    $location->delete();
                }

                $plans = $package->packagePlanList()->get();
                foreach ($plans as $plan) {
                    $plan->delete();
                }

                // first, delete all the contents of this package
                $contents = $package->packageContent()->get();

                foreach ($contents as $content) {
                    $content->delete();
                }

                // second, delete all the slider images of this package
                if (!is_null($package->slider_imgs)) {
                    $images = json_decode($package->slider_imgs);

                    foreach ($images as $image) {
                        if (file_exists(public_path('assets/img/packages/slider_images/') . $image)) {
                            @unlink(public_path('assets/img/packages/slider_images/') . $image);
                        }
                    }
                }

                // third, delete featured image of this package
                if (!is_null($package->featured_img) && file_exists(public_path('assets/img/packages/') . $package->featured_img)) {
                    @unlink(public_path('assets/img/packages/') . $package->featured_img);
                }

                // finally, delete this package
                $package->delete();
            }

            //delete all support ticket
            $support_tickets = SupportTicket::where([['user_id', $vendor->id], ['user_type', 'vendor']])->get();

            if (count($support_tickets) > 0) {
                foreach ($support_tickets as $support_ticket) {
                    //delete conversation 
                    $messages = $support_ticket->messages()->get();
                    foreach ($messages as $message) {
                        @unlink(public_path('assets/img/support-ticket/' . $message->file));
                        $message->delete();
                    }
                    @unlink(public_path('assets/img/support-ticket/') . $support_ticket->attachment);
                    $support_ticket->delete();
                }
            }
            //withdraws
            $withdraws = $vendor->withdraws()->get();
            foreach ($withdraws as $withdraw) {
                $withdraw->delete();
            }
            /*********************************************/
            #====finally delete the vendor=======
            @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);

            //vendor_infos
            $vendorInfos = VendorInfo::where('vendor_id', $vendor->id)->get();
            foreach ($vendorInfos as $vendorInfo) {
                $vendorInfo->delete();
            }
            $vendor->delete();
        }

        Session::flash('success', 'Vendors info deleted successfully!');

        return 'success';
    }

    public function secret_login($id)
    {
        $vendor = Vendor::where('id', $id)->first();
        Auth::guard('vendor')->login($vendor);
        Session::put('secret_login', 1);
        return redirect()->route('vendor.dashboard');
    }

    private function sendMail($data)
    {
        // first get the mail template information from db
        $mailTemplate = MailTemplate::where('mail_type', 'vendor_added')->firstOrFail();
        $mailSubject = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // second get the website title & mail's smtp information from db
        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        // replace template's curly-brace string with actual data
        $mailBody = str_replace('{username}', $data['username'], $mailBody);
        $mailBody = str_replace('{password}', $data['password'], $mailBody);
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
            // Recipients
            $mail->setFrom($info->from_mail, $info->from_name);
            $mail->addAddress($data['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body    = $mailBody;

            $mail->send();

            return;
        } catch (Exception $e) {
            return redirect()->back()->with('warning', 'Mail could not be sent!');
        }
    }
}
