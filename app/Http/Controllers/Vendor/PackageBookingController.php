<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\PackageManagement\PackageBooking;
use App\Models\PackageManagement\PackageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class PackageBookingController extends Controller
{
    public function bookings(Request $request)
    {
        $booking_number = $title = null;

        if ($request->filled('booking_no')) {
            $booking_number = $request['booking_no'];
        }
        $packageIds = [];
        if ($request->input('title')) {
            $title = $request->title;
            $package_contents = PackageContent::where('title', 'like', '%' . $title . '%')->get();
            foreach ($package_contents as $package_content) {
                if (!in_array($package_content->package_id, $packageIds)) {
                    array_push($packageIds, $package_content->package_id);
                }
            }
        }

        if (URL::current() == Route::is('vendor.package_bookings.all_bookings')) {
            $bookings = PackageBooking::where('vendor_id', Auth::guard('vendor')->user()->id)->when($booking_number, function ($query, $booking_number) {
                return $query->where('booking_number', 'like', '%' . $booking_number . '%');
            })
                ->when($title, function ($query) use ($packageIds) {
                    return $query->whereIn('package_id', $packageIds);
                })
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else if (URL::current() == Route::is('vendor.package_bookings.paid_bookings')) {
            $bookings = PackageBooking::where('vendor_id', Auth::guard('vendor')->user()->id)->when($booking_number, function ($query, $booking_number) {
                return $query->where('booking_number', 'like', '%' . $booking_number . '%');
            })
                ->when($title, function ($query) use ($packageIds) {
                    return $query->whereIn('package_id', $packageIds);
                })
                ->where('payment_status', 1)
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else if (URL::current() == Route::is('vendor.package_bookings.unpaid_bookings')) {
            $bookings = PackageBooking::where('vendor_id', Auth::guard('vendor')->user()->id)->when($booking_number, function ($query, $booking_number) {
                return $query->where('booking_number', 'like', '%' . $booking_number . '%');
            })
                ->when($title, function ($query) use ($packageIds) {
                    return $query->whereIn('package_id', $packageIds);
                })
                ->where('payment_status', 0)
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

        return view('vendors.packages.bookings', compact('bookings'));
    }

    public function updatePaymentStatus(Request $request)
    {
        $packageBooking = PackageBooking::where('id', $request->booking_id)->first();

        if ($request->payment_status == 1) {
            $packageBooking->update(['payment_status' => 1]);
        } else {
            $packageBooking->update(['payment_status' => 0]);
        }

        // delete previous invoice from local storage
        if (
            !is_null($packageBooking->invoice) &&
            file_exists(public_path('assets/invoices/packages/') . $packageBooking->invoice)
        ) {
            @unlink(public_path('assets/invoices/packages/') . $packageBooking->invoice);
        }

        // then, generate an invoice in pdf format
        $invoice = $this->generateInvoice($packageBooking);

        // update the invoice field information in database
        $packageBooking->update(['invoice' => $invoice]);

        // finally, send a mail to the customer with the invoice
        $this->sendMailForPaymentStatus($packageBooking, $request->payment_status);

        session()->flash('success', 'Payment status updated successfully!');

        return redirect()->back();
    }

    public function bookingDetails($id)
    {
        $details = PackageBooking::where('id', $id)->firstOrFail();

        $language = Language::where('is_default', 1)->firstOrFail();

        /**
         * to get the package title first get the package info using eloquent relationship
         * then, get the package content info of that package using eloquent relationship
         * after that, we can access the package title
         * also, get the package category using eloquent relationship
         */
        $packageInfo = $details->tourPackage()->firstOrFail();

        $packageContentInfo = $packageInfo->packageContent()->where('language_id', $language->id)
            ->firstOrFail();
        if ($packageContentInfo) {
            $packageTitle = $packageContentInfo->title;

            $packageCategoryInfo = $packageContentInfo->packageCategory()->first();

            if (!is_null($packageCategoryInfo)) {
                $packageCategoryName = $packageCategoryInfo->name;
            } else {
                $packageCategoryName = null;
            }
        } else {
            $packageTitle = '';
            $packageCategoryName = null;
        }


        return view(
            'vendors.packages.booking_details',
            compact('details', 'packageTitle', 'packageCategoryName')
        );
    }

    public function sendMail(Request $request)
    {
        $rules = [
            'subject' => 'required',
            'message' => 'required',
        ];

        $messages = [
            'subject.required' => 'The email subject field is required.',
            'message.required' => 'The email message field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        // get the mail's smtp information from db

        $mailInfo = DB::table('basic_settings')
            ->select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first(); //code...

        // initialize a new mail
        $mail = new PHPMailer(true);

        // if smtp status == 1, then set some value for PHPMailer
        if ($mailInfo->smtp_status == 1) {
            $mail->isSMTP();
            $mail->Host       = $mailInfo->smtp_host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailInfo->smtp_username;
            $mail->Password   = $mailInfo->smtp_password;

            if ($mailInfo->encryption == 'TLS') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port       = $mailInfo->smtp_port;
        }

        // finally add other informations and send the mail
        try {
            // Recipients
            $mail->setFrom($mailInfo->from_mail, $mailInfo->from_name);
            $mail->addAddress($request->customer_email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $request->subject;
            $mail->Body    = Purifier::clean($request->message, 'youtube');

            $mail->send();

            session()->flash('success', 'Mail has been sent!');

            /**
             * this 'success' is returning for ajax call.
             * if return == 'success' then ajax will reload the page.
             */
            return 'success';
        } catch (Exception $e) {
            session()->flash('warning', 'Mail could not be sent!');

            /**
             * this 'success' is returning for ajax call.
             * if return == 'success' then ajax will reload the page.
             */
            return 'success';
        }
    }

    public function deleteBooking(Request $request, $id)
    {
        $packageBooking = PackageBooking::where('id', $id)->first();

        // first, delete the attachment
        if (
            !is_null($packageBooking->attachment) &&
            file_exists(public_path('assets/img/attachments/packages/') . $packageBooking->attachment)
        ) {
            @unlink(public_path('assets/img/attachments/packages/') . $packageBooking->attachment);
        }

        // second, delete the invoice
        if (
            !is_null($packageBooking->invoice) &&
            file_exists(public_path('assets/invoices/packages/') . $packageBooking->invoice)
        ) {
            @unlink(public_path('assets/invoices/packages/') . $packageBooking->invoice);
        }

        // finally, delete the package booking record from db
        $packageBooking->delete();

        session()->flash('success', 'Package booking record deleted successfully!');

        return redirect()->back();
    }

    public function bulkDeleteBooking(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $packageBooking = PackageBooking::where('id', $id)->first();

            // first, delete the attachment
            if (
                !is_null($packageBooking->attachment) &&
                file_exists(public_path('assets/img/attachments/packages/') . $packageBooking->attachment)
            ) {
                @unlink(public_path('assets/img/attachments/packages/') . $packageBooking->attachment);
            }

            // second, delete the invoice
            if (
                !is_null($packageBooking->invoice) &&
                file_exists(public_path('assets/invoices/packages/') . $packageBooking->invoice)
            ) {
                @unlink(public_path('assets/invoices/packages/') . $packageBooking->invoice);
            }

            // finally, delete the package booking record from db
            $packageBooking->delete();
        }

        session()->flash('success', 'Package booking records deleted successfully!');

        /**
         * this 'success' is returning for ajax call.
         * if return == 'success' then ajax will reload the page.
         */
        return 'success';
    }
}
