<?php

namespace App\Http\Controllers\FrontEnd\Room;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\RoomManagement\RoomCategory;
use App\Models\RoomManagement\RoomBooking;
use App\Models\RoomManagement\RoomAmenity;
use App\Models\RoomManagement\RoomContent;
use App\Models\RoomManagement\RoomReview;
use App\Models\PackageManagement\Package;
use Illuminate\Support\Facades\Validator;
use App\Models\RoomManagement\Coupon;
use Illuminate\Support\Facades\Auth;
use App\Models\BasicSettings\Basic;
use App\Models\RoomManagement\Room;
use Illuminate\Support\Facades\DB;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Carbon\Carbon;

class RoomController extends Controller
{
  use MiscellaneousTrait;

  public function rooms(Request $request)
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();
    $roomRating = DB::table('basic_settings')->select('room_rating_status', 'room_category_status')->first();
    $queryResult['roomRating'] = $roomRating;

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['pageHeading'] = MiscellaneousTrait::getPageHeading($language);

    $num_of_bed = $num_of_bath = $num_of_guests = $min_rent = $max_rent = $location = null;

    $roomIds = [];
    $dates = [];

    if ($request->filled('dates')) {
      $dateArray = explode(' ', $request->dates);
      $date1 = $dateArray[0];
      $date2 = $dateArray[2];

      $dates = $this->displayDates($date1, $date2);

      $rooms = Room::all();

      foreach ($rooms as $key => $room) {
        foreach ($dates as $key => $date) {
          $cDate = Carbon::parse($date);
          $count = RoomBooking::whereDate('arrival_date', '<=', $cDate)->whereDate('departure_date', '>', $cDate)->where('room_id', $room->id)->count();

          if ($count >= $room->quantity) {
            if (!in_array($room->id, $roomIds)) {
              $roomIds[] = $room->id;
            }
          }
        }
      }
    }

    if ($request->filled('beds')) {
      $num_of_bed = $request->beds;
    }
    if ($request->filled('location')) {
      $location = $request->location;
    }
    if ($request->filled('baths')) {
      $num_of_bath = $request->baths;
    }
    if ($request->filled('guests')) {
      $num_of_guests = $request->guests;
    }
    if ($request->filled('rents')) {
      $bs = Basic::select('base_currency_symbol')->first();
      $rents = str_replace($bs->base_currency_symbol, ' ', $request->rents);
      $rentArray = explode(' ', $rents);
      $min_rent = $rentArray[1];
      $max_rent = $rentArray[4];
    }

    $category = $request->category;
    $sortBy = $request->sort_by;
    $ammenities = $request->ammenities;

    $queryResult['categories'] = RoomCategory::where('language_id', $language->id)
      ->where('status', 1)
      ->orderBy('serial_number', 'asc')
      ->get();

    if ($roomRating->room_category_status == 1) {
      $queryResult['roomInfos'] = DB::table('rooms')
        ->join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
        ->join('room_categories', 'room_contents.room_category_id', '=', 'room_categories.id')
        ->where('rooms.status', '=', 1)
        ->where('room_contents.language_id', '=', $language->id)
        ->when($category, function ($query) use ($category) {
          return $query->where('room_contents.room_category_id', $category);
        })->when($num_of_guests, function ($query, $num_of_guests) {
          return $query->where('max_guests', $num_of_guests);
        })->when($num_of_bed, function ($query, $num_of_bed) {
          return $query->where('bed', $num_of_bed);
        })->when($num_of_bath, function ($query, $num_of_bath) {
          return $query->where('bath', $num_of_bath);
        })->when(($min_rent && $max_rent), function ($query) use ($min_rent, $max_rent) {
          return $query->where('rent', '>=', $min_rent)->where('rent', '<=', $max_rent);
        })->when($ammenities, function ($query, $ammenities) {
          return $query->where(function ($query) use ($ammenities) {
            foreach ($ammenities as $key => $amm) {
              if ($key == 0) {
                $query->where('room_contents.amenities', 'LIKE',  "%" . '"' . $amm . '"' . "%");
              } else {
                $query->orWhere('room_contents.amenities', 'LIKE', "%" . '"' . $amm . '"' . "%");
              }
            }
          });
        })->when($location, function ($query) use ($location) {
          return $query->where('address', 'LIKE', '%' . $location . '%');
        })
        ->when($sortBy, function ($query, $sortBy) {
          if ($sortBy == 'asc') {
            return $query->orderBy('rooms.id', 'ASC');
          } elseif ($sortBy == 'desc') {
            return $query->orderBy('rooms.id', 'DESC');
          } elseif ($sortBy == 'price-desc') {
            return $query->orderBy('rent', 'DESC');
          } elseif ($sortBy == 'price-asc') {
            return $query->orderBy('rent', 'ASC');
          }
        }, function ($query) {
          return $query->orderBy('rooms.id', 'DESC');
        })
        ->whereNotIn('rooms.id', $roomIds)
        ->paginate(6);
    } else {
      $queryResult['roomInfos'] = DB::table('rooms')
        ->join('room_contents', 'rooms.id', '=', 'room_contents.room_id')
        ->where('rooms.status', '=', 1)
        ->where('room_contents.language_id', '=', $language->id)
        ->when($num_of_guests, function ($query, $num_of_guests) {
          return $query->where('max_guests', $num_of_guests);
        })->when($num_of_bed, function ($query, $num_of_bed) {
          return $query->where('bed', $num_of_bed);
        })->when($num_of_bath, function ($query, $num_of_bath) {
          return $query->where('bath', $num_of_bath);
        })->when(($min_rent && $max_rent), function ($query) use ($min_rent, $max_rent) {
          return $query->where('rent', '>=', $min_rent)->where('rent', '<=', $max_rent);
        })->when($ammenities, function ($query, $ammenities) {
          return $query->where(function ($query) use ($ammenities) {
            foreach ($ammenities as $key => $amm) {
              if ($key == 0) {
                $query->where('room_contents.amenities', 'LIKE',  "%" . '"' . $amm . '"' . "%");
              } else {
                $query->orWhere('room_contents.amenities', 'LIKE', "%" . '"' . $amm . '"' . "%");
              }
            }
          });
        })->when($sortBy, function ($query, $sortBy) {
          if ($sortBy == 'asc') {
            return $query->orderBy('rooms.id', 'ASC');
          } elseif ($sortBy == 'desc') {
            return $query->orderBy('rooms.id', 'DESC');
          } elseif ($sortBy == 'price-desc') {
            return $query->orderBy('rent', 'DESC');
          } elseif ($sortBy == 'price-asc') {
            return $query->orderBy('rent', 'ASC');
          }
        }, function ($query) {
          return $query->orderBy('rooms.id', 'DESC');
        })
        ->whereNotIn('rooms.id', $roomIds)
        ->paginate(6);
    }

    $queryResult['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

    $queryResult['numOfBed'] = Room::where('status', 1)->max('bed');

    $queryResult['numOfBath'] = Room::where('status', 1)->max('bath');

    $maxPrice = Room::where('status', 1)->max('rent');
    $minPrice = Room::where('status', 1)->min('rent');
    $maxGuests = Room::where('status', 1)->max('max_guests');

    $queryResult['maxPrice'] = $maxPrice;
    $queryResult['minPrice'] = $minPrice;
    $queryResult['maxGuests'] = $maxGuests;

    if ($request->filled('rents')) {
      $queryResult['maxRent'] = $max_rent;
      $queryResult['minRent'] = $min_rent;
    } else {
      $queryResult['maxRent'] = $maxPrice;
      $queryResult['minRent'] = $minPrice;
    }

    $queryResult['amenities'] = RoomAmenity::where('language_id', $language->id)->get();

    return view('frontend.rooms.rooms', $queryResult);
  }

  public function displayDates($date1, $date2, $format = 'Y-m-d')
  {
    $dates = array();
    $current = strtotime($date1);
    $date2 = strtotime($date2);
    $stepVal = '+1 day';

    while ($current < $date2) {
      $dates[] = date($format, $current);
      $current = strtotime($stepVal, $current);
    }

    return $dates;
  }

  public function roomDetails($id, $slug)
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

    $language = MiscellaneousTrait::getLanguage();

    $details = RoomContent::join('rooms', 'rooms.id', 'room_contents.room_id')
      ->where('language_id', $language->id)
      ->where('room_id', $id)
      ->firstOrFail();

    $queryResult['details'] = $details;

    $amms = [];

    if (!empty($details->amenities) && $details->amenities != '[]') {
      $ammIds = json_decode($details->amenities, true);
      $ammenities = RoomAmenity::whereIn('id', $ammIds)->orderBy('serial_number', 'ASC')->get();
      foreach ($ammenities as $key => $ammenity) {
        $amms[] = $ammenity->name;
      }
    }

    $queryResult['amms'] = $amms;

    $queryResult['reviews'] = RoomReview::where('room_id', $id)->orderBy('id', 'DESC')->get();

    $queryResult['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

    $queryResult['status'] = DB::table('basic_settings')
      ->select('room_rating_status', 'room_guest_checkout_status')
      ->where('uniqid', '=', 12345)
      ->first();

    $bookings = RoomBooking::where('room_id', $id)
      ->select('id', 'arrival_date', 'departure_date')
      ->where('payment_status', 1)
      ->get();

    $qty = Room::where('id', $id)->first()->quantity;

    $bookedDates = [];

    foreach ($bookings as $key => $booking) {
      // get all dates of a booking date range
      $dates = [];
      $dates = $this->displayDates($booking->arrival_date, $booking->departure_date);

      // loop through the dates
      foreach ($dates as $key => $date) {
        $count = 1;

        foreach ($bookings as $key => $cbooking) {
          if ($cbooking->id != $booking->id) {
            $start = Carbon::parse($cbooking->arrival_date);
            $departure = Carbon::parse($cbooking->departure_date)->subDay();
            $cDate = Carbon::parse($date);

            // check if the date is present in other booking's date ranges
            if ($cDate->gte($start) && $cDate->lte($departure)) {
              $count++;
            }
          }
        }

        // number of booking of a date is equal to room quantity, then mark the date as booked
        if ($count >= $qty && !in_array($date, $bookedDates)) {
          $bookedDates[] = $date;
        }
      }
    }

    $queryResult['bookingDates'] = $bookedDates;

    $queryResult['onlineGateways'] = OnlineGateway::where('status', 1)->get();

    $queryResult['offlineGateways'] = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get()->map(function ($gateway) {
      return [
        'id' => $gateway->id,
        'name' => $gateway->name,
        'short_description' => $gateway->short_description,
        'instructions' => replaceBaseUrl($gateway->instructions, 'summernote'),
        'attachment_status' => $gateway->attachment_status,
        'serial_number' => $gateway->serial_number
      ];
    });

    $queryResult['latestRooms'] = RoomContent::where('language_id', $language->id)->with(['room' => function ($query) {
      $query->status();
    }])
      ->where('room_id', '<>', $details->room_id)
      ->where('room_category_id', $details->room_category_id)
      ->orderBy('room_id', 'desc')
      ->limit(3)
      ->get();

    $queryResult['avgRating'] = RoomReview::where('room_id', $id)->avg('rating');

    return view('frontend.rooms.room_details', $queryResult);
  }

  public function applyCoupon(Request $request)
  {
    try {
      $coupon = Coupon::where('code', $request->coupon)->firstOrFail();

      $startDate = Carbon::parse($coupon->start_date);
      $endDate = Carbon::parse($coupon->end_date);
      $todayDate = Carbon::now();

      // check coupon is valid or not
      if ($todayDate->between($startDate, $endDate) == false) {
        return response()->json(['error' => 'Sorry, coupon has been expired!']);
      }

      // check coupon is valid or not for this room
      $roomId = $request->roomId;
      $roomIds = empty($coupon->rooms) ? '' : json_decode($coupon->rooms);

      if (!empty($roomIds) && !in_array($roomId, $roomIds)) {
        return response()->json(['error' => 'You can not apply this coupon for this room!']);
      }

      session()->put('couponCode', $request->coupon);

      $initTotalRent = str_replace(',', '', $request->initTotal);

      if ($initTotalRent == '0.00') {
        return response()->json(['error' => 'First, fillup the booking dates.']);
      } else {
        if ($coupon->type == 'fixed') {
          $total = floatval($initTotalRent) - floatval($coupon->value);

          return response()->json([
            'success' => 'Coupon applied successfully.',
            'discount' => $coupon->value,
            'total' => $total,
          ]);
        } else {
          $initTotalRent = floatval($initTotalRent);
          $couponVal = floatval($coupon->value);

          $discount = $initTotalRent * ($couponVal / 100);
          $total = $initTotalRent - $discount;

          return response()->json([
            'success' => 'Coupon applied successfully.',
            'discount' => $discount,
            'total' => $total
          ]);
        }
      }
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => 'Coupon is not valid!']);
    }
  }

  public function remove_coupon()
  {
    session()->forget('couponCode');
  }

  public function storeReview(Request $request, $id)
  {
    $booking = RoomBooking::where('user_id', Auth::user()->id)->where('room_id', $id)->where('payment_status', 1)->count();

    if ($booking == 0) {
      session()->flash('error', "You had not booked this room yet.");

      return back();
    }

    $rules = ['rating' => 'required|numeric'];

    $message = [
      'rating.required' => 'The star rating field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = Auth::guard('web')->user();

    $review = RoomReview::where('user_id', $user->id)->where('room_id', $id)->first();

    /**
     * if, room review of auth user does not exist then create a new one.
     * otherwise, update the existing review of that auth user.
     */
    if ($review == null) {
      RoomReview::create($request->except('user_id', 'room_id') + [
        'user_id' => $user->id,
        'room_id' => $id
      ]);

      // now, store the average rating of this room
      $room = Room::where('id', $id)->first();

      $room->update(['avg_rating' => $request->rating]);
    } else {
      $review->update($request->all());

      // now, get the average rating of this room
      $roomReviews = RoomReview::where('room_id', $id)->get();

      $totalRating = 0;

      foreach ($roomReviews as $roomReview) {
        $totalRating += $roomReview->rating;
      }

      $avgRating = $totalRating / $roomReviews->count();

      // finally, store the average rating of this room
      $room = Room::where('id', $id)->first();

      $room->update(['avg_rating' => $avgRating]);
    }

    if ($room->vendor_id != NULL) {
      $room_review_avg = Room::where('vendor_id', $room->vendor_id)->avg('avg_rating');
      $package_review_avg = Package::where('vendor_id', $room->vendor_id)->avg('avg_rating');


      $avg = ($room_review_avg + $package_review_avg) / 2;

      $vendor = Vendor::where('id', $room->vendor_id)->first();
      $vendor->avg_rating = $avg;
      $vendor->save();
    }

    session()->flash('success', 'Review saved successfully!');

    return redirect()->back();
  }
}
