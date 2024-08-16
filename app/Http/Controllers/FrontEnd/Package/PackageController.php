<?php

namespace App\Http\Controllers\FrontEnd\Package;

use App\Http\Controllers\Controller;
use App\Models\PackageManagement\Coupon;
use App\Models\PackageManagement\Package;
use App\Models\PackageManagement\PackageBooking;
use App\Models\PackageManagement\PackageCategory;
use App\Models\PackageManagement\PackageContent;
use App\Models\PackageManagement\PackageLocation;
use App\Models\PackageManagement\PackagePlan;
use App\Models\PackageManagement\PackageReview;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\RoomManagement\Room;
use App\Models\Vendor;
use App\Traits\MiscellaneousTrait;
use Carbon\Carbon;
use Config;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
  use MiscellaneousTrait;

  public function packages(Request $request)
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();
    $queryResult['packageRating'] = DB::table('basic_settings')->select('package_rating_status')->first();

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['categories'] = PackageCategory::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'ASC')->get();

    $queryResult['pageHeading'] = MiscellaneousTrait::getPageHeading($language);

    $queryResult['maxPrice'] = Package::max('package_price');

    $queryResult['minPrice'] = Package::min('package_price');
    $queryResult['maxPersons'] = Package::max('max_persons');
    $queryResult['maxDays'] = Package::max('number_of_days');

    $package_name = $sort_value = $location_name = $min_price = $max_price = null;

    $category = $request->category;
    if ($request->filled('packageName')) {
      $package_name = $request->packageName;
    }
    if ($request->filled('personsValue')) {
      $persons_value = $request->personsValue;
    } else {
      $persons_value = 0;
    }
    if ($request->filled('daysValue')) {
      $days_value = $request->daysValue;
    } else {
      $days_value = 0;
    }
    $sort_value = $request->sortValue;
    $true = true;
    $location_name = $request->locationName;
    if ($request->filled('minPrice') && $request->filled('maxPrice')) {
      $min_price = $request->minPrice;
      $max_price = $request->maxPrice;
    }

    $packageIds = [];
    if (!empty($location_name)) {
      $locations = PackageLocation::select('package_id')->where('name', 'LIKE', '%' . $location_name . '%')->get();
      foreach ($locations as $key => $location) {
        if (!in_array($location->package_id, $packageIds)) {
          $packageIds[] = $location->package_id;
        }
      }
    }

    $packageInfos = Package::join('package_contents', 'packages.id', '=', 'package_contents.package_id')
      ->where('package_contents.language_id', '=', $language->id)
      ->when($category, function ($query, $category) {
        return $query->where('package_contents.package_category_id', $category);
      })
      ->when($package_name, function ($query, $package_name) {
        return $query->where('title', 'like', '%' . $package_name . '%');
      })->when(($min_price && $max_price), function ($query) use ($min_price, $max_price) {
        return $query->where('package_price', '>=', $min_price)->where('package_price', '<=', $max_price);
      })->when($days_value, function ($query, $days_value) {
        return $query->where('number_of_days', '<=', $days_value);
      })->when($persons_value, function ($query, $persons_value) {
        return $query->where('max_persons', '>=', $persons_value);
      })->when($location_name, function ($query) use ($packageIds) {
        return $query->whereIn('packages.id', $packageIds);
      });


    if ($sort_value == 'new-packages') {
      $packageInfos->orderBy('packages.created_at', 'desc');
    } else if ($sort_value == 'old-packages') {
      $packageInfos->orderBy('packages.created_at', 'asc');
    } else if ($sort_value == 'price-asc') {
      $packageInfos->orderBy('packages.package_price', 'asc');
    } else if ($sort_value == 'price-desc') {
      $packageInfos->orderBy('packages.package_price', 'desc');
    } else if ($sort_value == 'max-persons-asc') {
      $packageInfos->orderBy('packages.max_persons', 'asc');
    } else if ($sort_value == 'max-persons-desc') {
      $packageInfos->orderBy('packages.max_persons', 'desc');
    } else if ($sort_value == 'days-asc') {
      $packageInfos->orderBy('packages.number_of_days', 'asc');
    } else if ($sort_value == 'days-desc') {
      $packageInfos->orderBy('packages.number_of_days', 'desc');
    } else if ($request->filled('number_of_days')) {
      $packageInfos->orderBy('packages.number_of_days', 'desc');
    } else {
      $packageInfos->orderByDesc('packages.id');
    }

    $queryResult['packageInfos'] = $packageInfos->paginate(6);

    $queryResult['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

    return view('frontend.packages.packages', $queryResult);
  }

  public function packageDetails($id)
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();
    $queryResult['packageRating'] = DB::table('basic_settings')->select('package_rating_status')->first();

    $language = MiscellaneousTrait::getLanguage();

    $details = PackageContent::with('package')
      ->where('language_id', $language->id)
      ->where('package_id', $id)
      ->firstOrFail();

    $queryResult['details'] = $details;

    $queryResult['plans'] = PackagePlan::where('language_id', $language->id)
      ->where('package_id', $id)
      ->get();

    $queryResult['locations'] = PackageLocation::where('language_id', $language->id)
      ->where('package_id', $id)
      ->get();

    $queryResult['reviews'] = PackageReview::where('package_id', $id)->orderBy('id', 'DESC')->get();

    $queryResult['status'] = DB::table('basic_settings')
      ->select('package_rating_status', 'package_guest_checkout_status')
      ->where('uniqid', '=', 12345)
      ->first();

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

    $queryResult['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

    $queryResult['latestPackages'] = PackageContent::with('package')
      ->where('language_id', $language->id)
      ->where('package_category_id', $details->package_category_id)
      ->where('package_id', '<>', $details->package_id)
      ->orderBy('package_id', 'desc')
      ->limit(3)
      ->get();

    $queryResult['avgRating'] = PackageReview::where('package_id', $id)->avg('rating');

    return view('frontend.packages.package_details', $queryResult);
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

      // check coupon is valid or not for this package
      $packageId = $request->packageId;
      $packageIds = empty($coupon->packages) ? '' : json_decode($coupon->packages);

      if (!empty($packageIds) && !in_array($packageId, $packageIds)) {
        return response()->json(['error' => 'You can not apply this coupon for this package!']);
      }

      session()->put('couponCode', $request->coupon);

      $initTotalRent = str_replace(',', '', $request->initTotal);

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
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => 'Coupon is not valid!']);
    }
  }

  public function removeCoupon()
  {
    session()->forget('couponCode');
  }

  public function storeReview(Request $request, $id)
  {
    $booking = PackageBooking::where('user_id', Auth::user()->id)->where('package_id', $id)->where('payment_status', 1)->count();
    if ($booking == 0) {
      session()->flash('error', "You had not purchased this package yet.");
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

    $review = PackageReview::where('user_id', $user->id)->where('package_id', $id)
      ->first();

    /**
     * if, package review of auth user does not exist then create a new one.
     * otherwise, update the existing review of that auth user.
     */
    if ($review == null) {
      PackageReview::create($request->except('user_id', 'package_id') + [
        'user_id' => $user->id,
        'package_id' => $id
      ]);

      // now, store the average rating of this package
      $package = Package::where('id', $id)->first();

      $package->update(['avg_rating' => $request->rating]);
    } else {
      $review->update($request->all());

      // now, get the average rating of this package
      $packageReviews = PackageReview::where('package_id', $id)->get();

      $totalRating = 0;

      foreach ($packageReviews as $packageReview) {
        $totalRating += $packageReview->rating;
      }

      $avgRating = $totalRating / $packageReviews->count();

      // finally, store the average rating of this package
      $package = Package::where('id', $id)->first();

      $package->update(['avg_rating' => $avgRating]);
    }

    if ($package->vendor_id != NULL) {
      $room_review_avg = Room::where('vendor_id', $package->vendor_id)->avg('avg_rating');
      $package_review_avg = Package::where('vendor_id', $package->vendor_id)->avg('avg_rating');

      $avg = ($room_review_avg + $package_review_avg) / 2;

      $vendor = Vendor::where('id', $package->vendor_id)->first();
      $vendor->avg_rating = $avg;
      $vendor->save();
    }

    session()->flash('success', 'Review saved successfully!');

    return redirect()->back();
  }
}
