<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRoomBookingRequest;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Language;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\RoomManagement\Room;
use App\Models\RoomManagement\RoomAmenity;
use App\Models\RoomManagement\RoomBooking;
use App\Models\RoomManagement\RoomContent;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Traits\MiscellaneousTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Mews\Purifier\Facades\Purifier;
use PHPMailer\PHPMailer\PHPMailer;

class RoomBookingController extends Controller
{
    use MiscellaneousTrait;

    public function bookings(Request $request)
    {
        $booking_number = $title = null;

        if ($request->filled('booking_no')) {
            $booking_number = $request['booking_no'];
        }

        $roomIds = [];
        if ($request->input('title')) {
            $title = $request->title;
            $room_contents = RoomContent::where('title', 'like', '%' . $title . '%')->get();
            foreach ($room_contents as $room_content) {
                if (!in_array($room_content->room_id, $roomIds)) {
                    array_push($roomIds, $room_content->room_id);
                }
            }
        }

        if (URL::current() == Route::is('vendor.room_bookings.all_bookings')) {
            $queryResult['bookings'] = RoomBooking::where('vendor_id', Auth::guard('vendor')->user()->id)
                ->when($booking_number, function ($query, $booking_number) {
                    return $query->where('booking_number', 'like', '%' . $booking_number . '%');
                })
                ->when($title, function ($query) use ($roomIds) {
                    return $query->whereIn('room_id', $roomIds);
                })
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else if (URL::current() == Route::is('vendor.room_bookings.paid_bookings')) {
            $queryResult['bookings'] = RoomBooking::where('vendor_id', Auth::guard('vendor')->user()->id)
                ->when($booking_number, function ($query, $booking_number) {
                    return $query->where('booking_number', 'like', '%' . $booking_number . '%');
                })
                ->when($title, function ($query) use ($roomIds) {
                    return $query->whereIn('room_id', $roomIds);
                })
                ->where('payment_status', 1)
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else if (URL::current() == Route::is('vendor.room_bookings.unpaid_bookings')) {
            $queryResult['bookings'] = RoomBooking::where('vendor_id', Auth::guard('vendor')->user()->id)
                ->when($booking_number, function ($query, $booking_number) {
                    return $query->where('booking_number', 'like', '%' . $booking_number . '%');
                })
                ->when($title, function ($query) use ($roomIds) {
                    return $query->whereIn('room_id', $roomIds);
                })
                ->where('payment_status', 0)
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

        $language = Language::query()->where('is_default', '=', 1)->first();

        $queryResult['roomInfos'] = $language->roomDetails()->whereHas('room', function (Builder $query) {
            $query->where('status', '=', 1);
        })
            ->select('room_id', 'title')
            ->orderBy('title', 'ASC')
            ->get();

        return view('vendors.rooms.bookings', $queryResult);
    }

    public function editBookingDetails($id)
    {
        $vendor_id = Auth::guard('vendor')->user()->id;
        $details = RoomBooking::where([['id', $id], ['vendor_id', $vendor_id]])->firstOrFail();
        $queryResult['details'] = $details;

        // get the difference of two dates, date should be in 'YYYY-MM-DD' format
        $date1 = new DateTime($details->arrival_date);
        $date2 = new DateTime($details->departure_date);
        $queryResult['interval'] = $date1->diff($date2, true);

        $language = Language::where('is_default', 1)->first();

        /**
         * to get the room title first get the room info using eloquent relationship
         * then, get the room content info of that room using eloquent relationship
         * after that, we can access the room title
         * also, get the room category using eloquent relationship
         */
        $roomInfo = $details->hotelRoom()->first();

        $roomContentInfo = $roomInfo->roomContent()->where('language_id', $language->id)->first();
        $queryResult['roomContentInfo'] = $roomContentInfo;
        if ($roomContentInfo) {
            $roomCategoryInfo = $roomContentInfo->roomCategory()->first();

            if ($roomCategoryInfo) {
                $queryResult['roomCategoryName'] = $roomCategoryInfo->name;
            } else {
                $queryResult['roomCategoryName'] = '';
            }
        } else {
            $queryResult['roomCategoryName'] = '';
        }



        // get all the booked dates of this room
        $roomId = $details->room_id;
        $detailsId = $details->id;

        $queryResult['bookedDates'] = $this->getBookedDatesOfRoom($roomId, $detailsId);

        $queryResult['onlineGateways'] = OnlineGateway::query()
            ->where('status', '=', 1)
            ->select('name')
            ->get();

        $queryResult['offlineGateways'] = OfflineGateway::query()
            ->where('status', '=', 1)
            ->select('name')
            ->orderBy('serial_number', 'asc')
            ->get();

        $queryResult['rent'] = $roomInfo->rent;

        return view('vendors.rooms.details', $queryResult);
    }


    public function deleteBooking(Request $request, $id)
    {
        $roomBooking = RoomBooking::where('id', $id)->first();

        // first, delete the attachment
        if (
            !is_null($roomBooking->attachment) &&
            file_exists(public_path('assets/img/attachments/rooms/') . $roomBooking->attachment)
        ) {
            @unlink(public_path('assets/img/attachments/rooms/') . $roomBooking->attachment);
        }

        // second, delete the invoice
        if (
            !is_null($roomBooking->invoice) &&
            file_exists(public_path('assets/invoices/rooms/') . $roomBooking->invoice)
        ) {
            @unlink(public_path('assets/invoices/rooms/') . $roomBooking->invoice);
        }

        // finally, delete the room booking record from db
        $roomBooking->delete();

        session()->flash('success', 'Room booking record deleted successfully!');

        return redirect()->back();
    }

    public function bulkDeleteBooking(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $roomBooking = RoomBooking::where('id', $id)->first();

            // first, delete the attachment
            if (
                !is_null($roomBooking->attachment) &&
                file_exists(public_path('assets/img/attachments/rooms/') . $roomBooking->attachment)
            ) {
                @unlink(public_path('assets/img/attachments/rooms/') . $roomBooking->attachment);
            }

            // second, delete the invoice
            if (
                !is_null($roomBooking->invoice) &&
                file_exists(public_path('assets/invoices/rooms/') . $roomBooking->invoice)
            ) {
                @unlink(public_path('assets/invoices/rooms/') . $roomBooking->invoice);
            }

            // finally, delete the room booking record from db
            $roomBooking->delete();
        }

        session()->flash('success', 'Room booking records deleted successfully!');

        /**
         * this 'success' is returning for ajax call.
         * if return == 'success' then ajax will reload the page.
         */
        return 'success';
    }


    private function generateInvoice($bookingInfo)
    {
        $fileName = $bookingInfo->booking_number . '.pdf';
        $directory = config('dompdf.public_path') . 'rooms/';

        if (!file_exists($directory)) {
            @mkdir($directory, 0775, true);
        }

        $fileLocated = $directory . $fileName;

        Pdf::loadView('frontend.pdf.room_booking', compact('bookingInfo'))->save($fileLocated);

        return $fileName;
    }

    // room booking from admin panel
    public function bookedDates(Request $request)
    {
        $rule = [
            'room_id' => 'required'
        ];

        $message = [
            'room_id.required' => 'Please select a room.'
        ];

        $validator = Validator::make($request->all(), $rule, $message);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->getMessageBag()
            ]);
        }

        // get all the booked dates of the selected room
        $roomId = $request['room_id'];

        $bookedDates = $this->getBookedDatesOfRoom($roomId);

        session()->put('bookedDates', $bookedDates);

        return response()->json([
            'success' => route('vendor.room_bookings.booking_form', ['room_id' => $roomId])
        ]);
    }

    public function getBookedDatesOfRoom($id, $bookingId = null)
    {
        $quantity = Room::query()->where('vendor_id', Auth::guard('vendor')->user()->id)->where('id', $id)->first()->quantity;

        $bookings = RoomBooking::query()->where('room_id', '=', $id)
            ->where('payment_status', '=', 1)
            ->select('arrival_date', 'departure_date')
            ->get();

        $bookedDates = [];

        foreach ($bookings as $booking) {
            // get all the dates between the booking arrival date & booking departure date
            $date_1 = $booking->arrival_date;
            $date_2 = $booking->departure_date;

            $allDates = $this->getAllDates($date_1, $date_2, 'Y-m-d');

            // loop through the list of dates, which we have found from the booking arrival date & booking departure date
            foreach ($allDates as $date) {
                $bookingCount = 0;

                // loop through all the bookings
                foreach ($bookings as $currentBooking) {
                    $bookingStartDate = Carbon::parse($currentBooking->arrival_date);
                    $bookingEndDate = Carbon::parse($currentBooking->departure_date);
                    $currentDate = Carbon::parse($date);

                    // check for each date, whether the date is present or not in any of the booking date range
                    if ($currentDate->betweenIncluded($bookingStartDate, $bookingEndDate)) {
                        $bookingCount++;
                    }
                }

                // if the number of booking of a specific date is same as the room quantity, then mark that date as unavailable
                if ($bookingCount >= $quantity && !in_array($date, $bookedDates)) {
                    array_push($bookedDates, $date);
                }
            }
        }

        if (is_null($bookingId)) {
            return $bookedDates;
        } else {
            $booking = RoomBooking::where('id', $bookingId)->first();
            $arrivalDate = $booking->arrival_date;
            $departureDate = $booking->departure_date;

            // get all the dates between the booking arrival date & booking departure date
            $bookingAllDates = $this->getAllDates($arrivalDate, $departureDate, 'Y-m-d');

            // remove dates of this booking from 'bookedDates' array while editing a room booking
            foreach ($bookingAllDates as $date) {
                $key = array_search($date, $bookedDates);

                if ($key !== false) {
                    unset($bookedDates[$key]);
                }
            }

            return array_values($bookedDates);
        }
    }

    public function getAllDates($startDate, $endDate, $format)
    {
        $dates = [];

        // convert string to timestamps
        $currentTimestamps = strtotime($startDate);
        $endTimestamps = strtotime($endDate);

        // set an increment value
        $stepValue = '+1 day';

        // push all the timestamps to the 'dates' array by formatting those timestamps into date
        while ($currentTimestamps <= $endTimestamps) {
            $formattedDate = date($format, $currentTimestamps);
            array_push($dates, $formattedDate);
            $currentTimestamps = strtotime($stepValue, $currentTimestamps);
        }

        return $dates;
    }

    public function bookingForm(Request $request)
    {
        if (session()->has('bookedDates')) {
            $queryResult['dates'] = session()->get('bookedDates');
        } else {
            $queryResult['dates'] = [];
        }

        $id = $request['room_id'];
        $queryResult['id'] = $id;

        $room = Room::where('id', $id)->first();;
        $queryResult['rent'] = $room->rent;

        $queryResult['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

        $queryResult['onlineGateways'] = OnlineGateway::query()
            ->where('status', '=', 1)
            ->select('name')
            ->get();

        $queryResult['offlineGateways'] = OfflineGateway::query()
            ->where('status', '=', 1)
            ->select('name')
            ->orderBy('serial_number', 'asc')
            ->get();

        return view('backend.rooms.booking_form', $queryResult);
    }

    public function makeBooking(AdminRoomBookingRequest $request)
    {
        $currencyInfo = MiscellaneousTrait::getCurrencyInfo();

        // store the room booking information in database
        $dateArray = explode(' ', $request->dates);

        $onlinePaymentGateway = ['PayPal', 'Stripe', 'Instamojo', 'Paystack', 'Flutterwave', 'Razorpay', 'MercadoPago', 'Mollie', 'Paytm'];

        $gatewayType = in_array($request->payment_method, $onlinePaymentGateway) ? 'online' : 'offline';

        $bookingInfo = RoomBooking::query()->create([
            'booking_number' => time(),
            'user_id' => null,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'room_id' => $request->room_id,
            'arrival_date' => $dateArray[0],
            'departure_date' => $dateArray[2],
            'guests' => $request->guests,
            'subtotal' => $request->subtotal,
            'discount' => $request->discount,
            'grand_total' => $request->total,
            'currency_symbol' => $currencyInfo->base_currency_symbol,
            'currency_symbol_position' => $currencyInfo->base_currency_symbol_position,
            'currency_text' => $currencyInfo->base_currency_text,
            'currency_text_position' => $currencyInfo->base_currency_text_position,
            'payment_method' => $request->payment_method,
            'gateway_type' => $gatewayType,
            'payment_status' => $request->payment_status
        ]);

        if ($request->payment_status == 1) {
            // generate an invoice in pdf format
            $invoice = $this->generateInvoice($bookingInfo);

            // update the invoice field information in database
            $bookingInfo->update(['invoice' => $invoice]);

            // send a mail to the customer with an invoice
            $this->sendMailForRoomBooking($bookingInfo);
        }

        session()->flash('success', 'Room has booked.');

        return redirect()->back();
    }

    public function sendMailForRoomBooking($bookingInfo)
    {
        // first get the mail template information from db
        $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'room_booking')->first();
        $mailSubject = $mailTemplate->mail_subject;
        $mailBody = replaceBaseUrl($mailTemplate->mail_body, 'summernote');

        // second get the website title & mail's smtp information from db
        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        // get the difference of two dates, date should be in 'YYYY-MM-DD' format
        $date1 = new DateTime($bookingInfo->arrival_date);
        $date2 = new DateTime($bookingInfo->departure_date);
        $interval = $date1->diff($date2, true);

        // get the room category name according to language
        $language = Language::query()->where('is_default', '=', 1)->first();

        $roomContent = RoomContent::query()->where('language_id', '=', $language->id)
            ->where('room_id', '=', $bookingInfo->room_id)
            ->first();

        $roomCategoryName = $roomContent->roomCategory->name;

        $roomRent = ($bookingInfo->currency_text_position == 'left' ? $bookingInfo->currency_text . ' ' : '') . $bookingInfo->grand_total . ($bookingInfo->currency_text_position == 'right' ? ' ' . $bookingInfo->currency_text : '');

        // get the amenities of booked room
        $amenityIds = json_decode($roomContent->amenities);

        $amenityArray = [];

        foreach ($amenityIds as $id) {
            $amenity = RoomAmenity::query()->where('id', $id)->first();
            array_push($amenityArray, $amenity->name);
        }

        // now, convert amenity array into comma separated string
        $amenityString = implode(', ', $amenityArray);

        // replace template's curly-brace string with actual data
        $mailBody = str_replace('{customer_name}', $bookingInfo->customer_name, $mailBody);
        $mailBody = str_replace('{room_name}', $roomContent->title, $mailBody);
        $mailBody = str_replace('{room_rent}', $roomRent, $mailBody);
        $mailBody = str_replace('{booking_number}', $bookingInfo->booking_number, $mailBody);
        $mailBody = str_replace('{booking_date}', date_format($bookingInfo->created_at, 'F d, Y'), $mailBody);
        $mailBody = str_replace('{number_of_night}', $interval->days, $mailBody);
        $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);
        $mailBody = str_replace('{check_in_date}', $bookingInfo->arrival_date, $mailBody);
        $mailBody = str_replace('{check_out_date}', $bookingInfo->departure_date, $mailBody);
        $mailBody = str_replace('{number_of_guests}', $bookingInfo->guests, $mailBody);
        $mailBody = str_replace('{room_type}', $roomCategoryName, $mailBody);
        $mailBody = str_replace('{room_amenities}', $amenityString, $mailBody);

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
            $mail->addAttachment(public_path('assets/invoices/rooms/') . $bookingInfo->invoice);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $mailSubject;
            $mail->Body    = $mailBody;

            $mail->send();

            return;
        } catch (Exception $e) {
            return;
        }
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
            ->where('uniqid', 12345)
            ->first();

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
}
