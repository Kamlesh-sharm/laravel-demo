<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\PackageManagement\Package;
use App\Models\PackageManagement\PackageCategory;
use App\Models\PackageManagement\PackageReview;
use App\Models\RoomManagement\Room;
use App\Models\RoomManagement\RoomCategory;
use App\Models\RoomManagement\RoomReview;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Traits\MiscellaneousTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;

class VendorController extends Controller
{
    use MiscellaneousTrait;
    //index
    public function index(Request $request)
    {
        $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

        $language = MiscellaneousTrait::getLanguage();

        $uo_name = $location = $rating = null;
        if ($request->filled('uo_name')) {
            $uo_name = $request->uo_name;
        }
        if ($request->filled('location')) {
            $location = $request->location;
        }
        if ($request->filled('rating')) {
            $rating = $request->rating;
        }

        $vendor_ids = [];

        if ($request->filled('location')) {
            $verdorInfos = VendorInfo::where('country', 'like', '%' . $location . '%')
                ->orWhere('city', 'like', '%' . $location . '%')
                ->orWhere('state', 'like', '%' . $location . '%')
                ->orWhere('zip_code', 'like', '%' . $location . '%')
                ->orWhere('address', 'like', '%' . $location . '%')
                ->get();
            foreach ($verdorInfos as $verdorInfo) {
                if (!in_array($verdorInfo->vendor_id, $vendor_ids)) {
                    array_push($vendor_ids, $verdorInfo->vendor_id);
                }
            }
        }
        $queryResult['vendors'] = Vendor::where('status', 1)
            ->when($uo_name, function ($query, $uo_name) {
                return $query->where('vendors.username', 'like', '%' . $uo_name . '%');
            })
            ->when($rating, function ($query, $rating) {
                return $query->where('vendors.avg_rating', '>=', $rating);
            })
            ->when($location, function ($query) use ($vendor_ids) {
                return $query->whereIn('vendors.id', $vendor_ids);
            })
            ->paginate(8);

        return view('frontend.vendor.index', $queryResult);
    }
    //details 
    public function details(Request $request)
    {
        $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

        $language = MiscellaneousTrait::getLanguage();
        $queryResult['roomRating'] = DB::table('basic_settings')->select('room_rating_status')->first();

        $language_id = $language->id;

        if (!$request->filled('admin')) {
            $vendor = Vendor::where('username', $request->username)->firstOrFail();
            $queryResult['vendor'] = $vendor;
            $vendor_id = $vendor->id;
        } else {
            $vendor = Admin::first();
            $queryResult['vendor'] = $vendor;
            $vendor_id = null;
        }

        //rooms
        $all_rooms = Room::where('vendor_id', $vendor_id)->with([
            'room_content' => function ($q) use ($language_id) {
                return $q->where('language_id', $language_id);
            }
        ])
            ->where('status', 1)
            ->get();

        //package
        $all_packages = Package::where('vendor_id', $vendor_id)->with([
            'package_content' => function ($q) use ($language_id) {
                return $q->where('language_id', $language_id);
            }
        ])->get();

        $queryResult['currencyInfo'] = $this->getCurrencyInfo();

        $queryResult['packageRating'] = DB::table('basic_settings')->select('package_rating_status')->first();
        $queryResult['room_categories'] = RoomCategory::where([['status', 1], ['language_id', $language_id]])->get();
        $queryResult['package_categories'] = PackageCategory::where([['status', 1], ['language_id', $language_id]])->get();

        $queryResult['language_id'] = $language_id;
        $queryResult['all_rooms'] = $all_rooms;

        $queryResult['all_packages'] = $all_packages;

        $roomIds = [];
        foreach ($all_rooms as $room) {
            if (!in_array($room->id, $roomIds)) {
                array_push($roomIds, $room->id);
            }
        }
        $room_review_avg = RoomReview::whereIn('room_id', $roomIds)->avg('rating');

        $packageIds = [];
        foreach ($all_packages as $package) {
            if (!in_array($package->id, $packageIds)) {
                array_push($packageIds, $package->id);
            }
        }
        $package_review_avg = PackageReview::whereIn('package_id', $packageIds)->avg('rating');
        if ($room_review_avg == null) {
            $vendor_avg_rating = (($room_review_avg + $package_review_avg) / 1);
        } elseif ($package_review_avg == null) {
            $vendor_avg_rating = (($room_review_avg + $package_review_avg) / 1);
        } else {
            $vendor_avg_rating = (($room_review_avg + $package_review_avg) / 2);
        }

        $queryResult['vendor_avg_rating'] = $vendor_avg_rating;
        return view('frontend.vendor.details', $queryResult);
    }
    

    //contact
    public function contact(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
            'message' => 'required'
        ];

        $request->validate($rules);


        $be = Basic::select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')->firstOrFail();
        if ($be->smtp_status == 1) {
            $subject = $request->subject;
            $msg = "
                    <h4>Name : $request->name</h4>
                    <h4>Email : $request->email</h4>
                    <p>Message : $request->message</p>
                    ";

            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = $be->smtp_host;                    // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = $be->smtp_username;                     // SMTP username
                $mail->Password   = $be->smtp_password;                               // SMTP password
                $mail->SMTPSecure = $be->encryption;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $mail->Port       = $be->smtp_port;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                //Recipients
                $mail->setFrom($be->from_mail, $be->from_name);
                $mail->addAddress($request->vendor_email);     // Add a recipient

                // Content
                $mail->isHTML(true);
                $mail->Subject =  $subject;
                $mail->Body    = $msg;
                $mail->send();
                Session::flash('success', 'Message sent successfully');
                return back();
            } catch (Exception $e) {
                Session::flash('error', $e);
                return back();
            }
        }
    }
}
