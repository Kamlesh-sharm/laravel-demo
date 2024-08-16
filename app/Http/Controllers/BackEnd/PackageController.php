<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Commission;
use App\Models\Earning;
use App\Models\Language;
use App\Models\PackageManagement\Coupon;
use App\Models\PackageManagement\Package;
use App\Models\PackageManagement\PackageBooking;
use App\Models\PackageManagement\PackageCategory;
use App\Models\PackageManagement\PackageContent;
use App\Models\PackageManagement\PackageImage;
use App\Models\PackageManagement\PackageLocation;
use App\Models\PackageManagement\PackagePlan;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Traits\MiscellaneousTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use PDF;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class PackageController extends Controller
{
  use MiscellaneousTrait;

  public function settings()
  {
    $data = DB::table('basic_settings')
      ->select('package_category_status', 'package_rating_status', 'package_guest_checkout_status')
      ->where('uniqid', 12345)
      ->first();

    return view('backend.packages.settings', ['data' => $data]);
  }

  public function updateSettings(Request $request)
  {
    $rules = [
      'package_category_status' => 'required',
      'package_rating_status' => 'required',
      'package_guest_checkout_status' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->update([
      'package_category_status' => $request->package_category_status,
      'package_rating_status' => $request->package_rating_status,
      'package_guest_checkout_status' => $request->package_guest_checkout_status
    ]);

    session()->flash('success', 'Package settings updated successfully!');

    return redirect()->back();
  }


  public function coupons()
  {
    // get the coupons from db
    $information['coupons'] = Coupon::orderByDesc('id')->get();

    // also, get the currency information from db
    $information['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

    $language = Language::where('is_default', 1)->first();

    $packages = Package::all();

    $packages->map(function ($package) use ($language) {
      $package['title'] = $package->packageContent()->where('language_id', $language->id)->pluck('title')->first();
    });

    $information['packages'] = $packages;

    return view('backend.packages.coupons', $information);
  }

  public function storeCoupon(CouponRequest $request)
  {
    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    if ($request->filled('packages')) {
      $packages = $request->packages;
    }

    Coupon::create($request->except('start_date', 'end_date', 'packages') + [
      'start_date' => date_format($startDate, 'Y-m-d'),
      'end_date' => date_format($endDate, 'Y-m-d'),
      'packages' => isset($packages) ? json_encode($packages) : null
    ]);

    session()->flash('success', 'New coupon added successfully!');

    return 'success';
  }

  public function updateCoupon(CouponRequest $request)
  {
    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    if ($request->filled('packages')) {
      $packages = $request->packages;
    }

    Coupon::where('id', $request->id)->first()->update($request->except('start_date', 'end_date', 'packages') + [
      'start_date' => date_format($startDate, 'Y-m-d'),
      'end_date' => date_format($endDate, 'Y-m-d'),
      'packages' => isset($packages) ? json_encode($packages) : null
    ]);

    session()->flash('success', 'Coupon updated successfully!');

    return 'success';
  }

  public function destroyCoupon($id)
  {
    Coupon::where('id', $id)->first()->delete();

    return redirect()->back()->with('success', 'Coupon deleted successfully!');
  }


  public function categories(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the package categories of that language from db
    $information['packageCategories'] = PackageCategory::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->paginate(10);

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.packages.categories', $information);
  }

  public function storeCategory(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'name' => 'required',
      'status' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $in = $request->all();

    PackageCategory::create($in);

    session()->flash('success', 'New package category added successfully!');

    return 'success';
  }

  public function updateCategory(Request $request)
  {
    $rules = [
      'name' => 'required',
      'status' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    PackageCategory::where('id', $request->category_id)->first()->update($request->all());

    session()->flash('success', 'Package category updated successfully!');

    return 'success';
  }

  public function deleteCategory(Request $request)
  {
    $packageCategory = PackageCategory::where('id', $request->category_id)->first();

    if ($packageCategory->packageContentList()->count() > 0) {
      session()->flash('warning', 'First delete all the packages of this category!');

      return redirect()->back();
    }

    $packageCategory->delete();

    session()->flash('success', 'Package category deleted successfully!');

    return redirect()->back();
  }

  public function bulkDeleteCategory(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $packageCategory = PackageCategory::where('id', $id)->first();

      if ($packageCategory->packageContentList()->count() > 0) {
        session()->flash('warning', 'First delete all the packages of those category!');

        /**
         * this 'success' is returning for ajax call.
         * here, by returning the 'success' ajax will show the flash error message
         */
        return 'success';
      }

      $packageCategory->delete();
    }

    session()->flash('success', 'Package categories deleted successfully!');

    /**
     * this 'success' is returning for ajax call.
     * if return == 'success' then ajax will reload the page.
     */
    return 'success';
  }


  public function packages(Request $request)
  {
    $language = Language::where('is_default', 1)->firstOrFail();
    $information['language'] = $language;

    $languageId = $language->id;

    $vendor = $title = null;
    if ($request->filled('vendor')) {
      $vendor = $request['vendor'];
    }
    if ($request->filled('title')) {
      $title = $request['title'];
    }

    $packageIds = [];
    if ($request->filled('title')) {
      $package_contents = PackageContent::where('title', 'like', '%' . $title . '%')->get();
      foreach ($package_contents as $package_content) {
        if (!in_array($package_content->package_id, $packageIds)) {
          array_push($packageIds, $package_content->package_id);
        }
      }
    }

    $information['packages'] = Package::with([
      'package_content' => function ($q) use ($languageId) {
        $q->where('language_id', $languageId);
      }
    ])
      ->when($vendor, function ($query, $vendor) {
        if ($vendor == 'admin') {
          return $query->where('packages.vendor_id', '=', null);
        } else {
          return $query->where('packages.vendor_id', $vendor);
        }
      })
      ->when($title, function ($query) use ($packageIds) {
        return $query->whereIn('id', $packageIds);
      })
      ->orderBy('id', 'desc')
      ->paginate(10);

    $information['vendors'] = Vendor::get();
    $information['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

    return view('backend.packages.packages', $information);
  }

  public function createPackage()
  {
    // get all the languages from db
    $information['languages'] = Language::all();

    $information['basicSettings'] = DB::table('basic_settings')
      ->select('package_category_status')
      ->where('uniqid', 12345)
      ->first();
    $information['vendors'] = Vendor::where('status', 1)->get();

    return view('backend.packages.create_package', $information);
  }

  public function gallerystore(Request $request)
  {
    $img = $request->file('file');
    $allowedExts = array('jpg', 'png', 'jpeg');
    $rules = [
      'file' => [
        'dimensions:width=750,height=400',
        function ($attribute, $value, $fail) use ($img, $allowedExts) {
          $ext = $img->getClientOriginalExtension();
          if (!in_array($ext, $allowedExts)) {
            return $fail("Only png, jpg, jpeg images are allowed");
          }
        }
      ]
    ];
    $messages = [
      'file.dimensions' => 'The file has invalid image dimensions ' . $img->getClientOriginalName()
    ];

    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      $validator->getMessageBag()->add('error', 'true');
      return response()->json($validator->errors());
    }
    $filename = uniqid() . '.jpg';
    @mkdir(public_path('assets/img/package-gallery/'), 0775, true);
    $img->move(public_path('assets/img/package-gallery/'), $filename);
    $pi = new PackageImage();
    if (!empty($request->package_id)) {
      $pi->package_id = $request->package_id;
    }
    $pi->image = $filename;
    $pi->save();
    return response()->json(['status' => 'success', 'file_id' => $pi->id]);
  }

  public function images($portid)
  {
    $images = PackageImage::where('package_id', $portid)->get();
    return $images;
  }

  public function imagedbrmv(Request $request)
  {
    $pi = PackageImage::where('id', $request->fileid)->first();
    $package_id = $pi->package_id;
    $image_count = PackageImage::where('package_id', $package_id)->get()->count();
    if ($image_count > 1) {
      @unlink(public_path('assets/img/package-gallery/') . $pi->image);
      $pi->delete();
      return $pi->id;
    } else {
      return 'false';
    }
  }

  public function storePackage(Request $request)
  {
    $rules = [
      'slider_images' => 'required',
      'number_of_days' => 'required|numeric|min:1',
      'plan_type' => 'required',
      'pricing_type' => 'required',
      'fixed_package_price' => 'required_if:pricing_type,==,fixed',
      'per_person_package_price' => 'required_if:pricing_type,==,per-person'
    ];

    $featuredImgURL = $request->featured_img;

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    $featuredImgExt = $featuredImgURL->getClientOriginalExtension();


    $rules['featured_img'] = [
      'required',
      'dimensions:width=300,height=360',
      function ($attribute, $value, $fail) use ($allowedExtensions, $featuredImgExt) {
        if (!in_array($featuredImgExt, $allowedExtensions)) {
          $fail('Only .jpg, .jpeg, .png and .svg file is allowed for featured image.');
        }
      }
    ];

    $messages = [
      'featured_img.required' => 'The package\'s featured image is required.',
    ];

    $languages = Language::all();

    $settings = DB::table('basic_settings')->select('package_category_status')
      ->where('uniqid', 12345)
      ->first();

    foreach ($languages as $language) {
      $rules[$language->code . '_title'] = 'required|max:255';

      if ($settings->package_category_status == 1) {
        $rules[$language->code . '_category'] = 'required';
      }

      $rules[$language->code . '_description'] = 'required|min:15';

      $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';

      $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

      if ($settings->package_category_status == 1) {
        $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language';
      }

      $messages[$language->code . '_description.required'] = 'The description field is required for ' . $language->name . ' language';

      $messages[$language->code . '_description.min'] = 'The description field atleast have 15 characters for ' . $language->name . ' language';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $package = new Package();
    if ($request->hasFile('featured_img')) {
      $filename = time() . '.' . $featuredImgURL->getClientOriginalExtension();
      $directory = public_path('assets/img/package/');
      @mkdir($directory, 0775, true);
      $request->file('featured_img')->move($directory, $filename);
      $package->featured_img = $filename;
    }
    $package->vendor_id = $request->vendor_id;
    $package->plan_type = $request->plan_type;
    $package->number_of_days = $request->number_of_days;
    $package->max_persons = $request->max_persons;
    $package->pricing_type = $request->pricing_type;

    if ($request->pricing_type == 'fixed') {
      $package->package_price = $request->fixed_package_price;
    } elseif ($request->pricing_type == 'per-person') {
      $package->package_price = $request->per_person_package_price;
    }

    $package->email = $request->email;
    $package->phone = $request->phone;
    $package->save();

    $slders = $request->slider_images;

    foreach ($slders as $key => $id) {
      $package_image = PackageImage::where('id', $id)->first();
      if ($package_image) {
        $package_image->package_id = $package->id;
        $package_image->save();
      }
    }

    foreach ($languages as $language) {
      $packageContent = new PackageContent();
      $packageContent->language_id = $language->id;
      $packageContent->package_category_id = $request[$language->code . '_category'];
      $packageContent->package_id = $package->id;
      $packageContent->title = $request[$language->code . '_title'];
      $packageContent->slug = createSlug($request[$language->code . '_title']);
      $packageContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
      $packageContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $packageContent->meta_description = $request[$language->code . '_meta_description'];
      $packageContent->save();
    }

    session()->flash('success', 'New tour package added successfully!');

    return 'success';
  }

  public function updateFeaturedPackage(Request $request)
  {
    $package = Package::where('id', $request->packageId)->first();

    if ($request->is_featured == 1) {
      $package->update(['is_featured' => 1]);

      session()->flash('success', 'Package featured successfully!');
    } else {
      $package->update(['is_featured' => 0]);

      session()->flash('success', 'Package Unfeatured successfully!');
    }

    return redirect()->back();
  }

  public function editPackage($id)
  {
    $information['package'] = Package::where('id', $id)->firstOrFail();

    // get all the languages from db
    $information['languages'] = Language::all();

    $information['basicSettings'] = DB::table('basic_settings')
      ->select('package_category_status')
      ->where('uniqid', 12345)
      ->first();

    $information['vendors'] = Vendor::where('status', 1)->get();

    return view('backend.packages.edit_package', $information);
  }

  public function updatePackage(Request $request, $id)
  {
    $rules = [
      'number_of_days' => 'required|numeric|min:1',
      'plan_type' => 'required',
      'pricing_type' => 'required',
      'fixed_package_price' => 'required_if:pricing_type,==,fixed',
      'per_person_package_price' => 'required_if:pricing_type,==,per-person'
    ];

    $featuredImgURL = $request->featured_img;
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');

    if ($request->hasFile('featured_img')) {
      $featuredImgExt = $featuredImgURL->getClientOriginalExtension();
      $rules['featured_img'] = function ($attribute, $value, $fail) use ($allowedExtensions, $featuredImgExt) {
        if (!in_array($featuredImgExt, $allowedExtensions)) {
          $fail('Only .jpg, .jpeg, .png and .svg file is allowed for featured image.');
        }
      };
      $rules['featured_img'] = 'dimensions:width=300,height=360';
    }


    $languages = Language::all();

    $settings = DB::table('basic_settings')->select('package_category_status')
      ->where('uniqid', 12345)
      ->first();

    foreach ($languages as $language) {
      $rules[$language->code . '_title'] = 'required|max:255';

      if ($settings->package_category_status == 1) {
        $rules[$language->code . '_category'] = 'required';
      }

      $rules[$language->code . '_description'] = 'required|min:15';

      $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';

      $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

      if ($settings->package_category_status == 1) {
        $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language';
      }

      $messages[$language->code . '_description.required'] = 'The description field is required for ' . $language->name . ' language';

      $messages[$language->code . '_description.min'] = 'The description field atleast have 15 characters for ' . $language->name . ' language';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $package = Package::where('id', $id)->first();

    if ($request->hasFile('featured_img')) {
      $filename = time() . '.' . $featuredImgURL->getClientOriginalExtension();
      $directory = public_path('assets/img/package/');
      @mkdir($directory, 0775, true);
      @unlink($directory . $package->featured_img);
      $request->file('featured_img')->move($directory, $filename);
      $featured_image = $filename;
    }

    // get the package price that admin has selected
    if ($request->pricing_type == 'negotiable') {
      $amount = null;
    } elseif ($request->pricing_type == 'fixed') {
      $amount = $request->fixed_package_price;
    } elseif ($request->pricing_type == 'per-person') {
      $amount = $request->per_person_package_price;
    }

    $package->update([
      'number_of_days' => $request->number_of_days,
      'vendor_id' => $request->vendor_id,
      'featured_img' => $request->hasFile('featured_img') ? $featured_image : $package->featured_img,
      'plan_type' => $request->plan_type,
      'max_persons' => $request->max_persons,
      'pricing_type' => $request->pricing_type,
      'package_price' => isset($amount) ? $amount : $package->package_price,
      'email' => $request->email,
      'phone' => $request->phone
    ]);

    foreach ($languages as $language) {
      $packageContent = PackageContent::where('package_id', $id)
        ->where('language_id', $language->id)
        ->first();

      $content = [
        'language_id' => $language->id,
        'package_id' => $package->id,
        'package_category_id' => $request[$language->code . '_category'],
        'title' => $request[$language->code . '_title'],
        'slug' => createSlug($request[$language->code . '_title']),
        'description' => Purifier::clean($request[$language->code . '_description'], 'youtube'),
        'meta_keywords' => $request[$language->code . '_meta_keywords'],
        'meta_description' => $request[$language->code . '_meta_description']
      ];

      if (!empty($packageContent)) {
        $packageContent->update($content);
      } else {
        PackageContent::create($content);
      }
    }

    session()->flash('success', 'Tour package updated successfully!');

    return 'success';
  }

  public function deletePackage(Request $request)
  {
    $package = Package::where('id', $request->package_id)->first();

    if ($package->packageLocationList()->count() > 0) {
      $locations = $package->packageLocationList()->get();
      foreach ($locations as $location) {
        $location->delete();
      }
    }

    if ($package->packagePlanList()->count() > 0) {
      $plans = $package->packagePlanList()->get();
      foreach ($plans as $plan) {
        $plan->delete();
      }
    }

    // first, delete all the contents of this package
    $contents = $package->packageContent()->get();

    foreach ($contents as $content) {
      $content->delete();
    }

    // second, delete all the slider images of this package
    $sliders = PackageImage::where('package_id', $package->id)->get();
    foreach ($sliders as $slider) {
      @unlink(public_path('assets/img/package-gallery/') . $slider->image);
    }

    // third, delete featured image of this package
    @unlink(public_path('assets/img/package/') . $package->featured_img);

    // finally, delete this package
    $package->delete();

    session()->flash('success', 'Tour package deleted successfully!');

    return redirect()->back();
  }

  public function bulkDeletePackage(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $package = Package::where('id', $id)->first();

      if ($package->packageLocationList()->count() > 0) {
        $locations = $package->packageLocationList()->get();
        foreach ($locations as $location) {
          $location->delete();
        }
      }

      if ($package->packagePlanList()->count() > 0) {
        $plans = $package->packagePlanList()->get();
        foreach ($plans as $plan) {
          $plan->delete();
        }
      }

      // first, delete all the contents of this package
      $contents = $package->packageContent()->get();

      foreach ($contents as $content) {
        $content->delete();
      }

      // second, delete all the slider images of this package
      $sliders = PackageImage::where('package_id', $package->id)->get();
      foreach ($sliders as $slider) {
        @unlink(public_path('assets/img/package-gallery/') . $slider->image);
      }

      // third, delete featured image of this package
      @unlink(public_path('assets/img/package/') . $package->featured_img);

      // finally, delete this package
      $package->delete();
    }

    session()->flash('success', 'Tour packages deleted successfully!');

    /**
     * this 'success' is returning for ajax call.
     * if return == 'success' then ajax will reload the page.
     */
    return 'success';
  }


  public function storeLocation(Request $request)
  {
    $rule = [
      'name' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $lang = Language::where('id', $request->language_id)->first();

    PackageLocation::create($request->except('language_id') + [
      'language_id' => $lang->id
    ]);

    session()->flash('success', 'New location added successfully!');

    return 'success';
  }

  public function viewLocations(Request $request, $package_id)
  {
    // first, get the language info from db
    $information['langs'] = Language::all();
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the locations of selected package
    $information['locations'] = PackageLocation::where('language_id', $language->id)
      ->where('package_id', $package_id)
      ->orderBy('id', 'desc')
      ->get();

    return view('backend.packages.locations', $information);
  }

  public function updateLocation(Request $request)
  {
    $rule = [
      'name' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    PackageLocation::where('id', $request->location_id)->first()->update($request->all());

    session()->flash('success', 'Location updated successfully!');

    return 'success';
  }

  public function deleteLocation(Request $request)
  {
    PackageLocation::where('id', $request->location_id)->first()->delete();

    session()->flash('success', 'Location deleted successfully!');

    return redirect()->back();
  }

  public function bulkDeleteLocation(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      PackageLocation::where('id', $id)->first()->delete();
    }

    session()->flash('success', 'Locations deleted successfully!');

    /**
     * this 'success' is returning for ajax call.
     * if return == 'success' then ajax will reload the page.
     */
    return 'success';
  }


  public function storeDaywisePlan(Request $request)
  {
    $rules = [
      'day_number' => 'required',
      'plan' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $lang = Language::where('id', $request->language_id)->first();

    PackagePlan::create($request->except('language_id') + [
      'language_id' => $lang->id,
      'plan' => Purifier::clean($request->plan, 'youtube')
    ]);

    session()->flash('success', 'New plan added successfully!');

    return Response::json('success', 200);
  }

  public function storeTimewisePlan(Request $request)
  {
    $rules = [
      'start_time' => 'required',
      'end_time' => 'required',
      'plan' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $lang = Language::where('id', $request->language_id)->first();

    PackagePlan::create($request->except('language_id') + [
      'language_id' => $lang->id,
      'plan' => Purifier::clean($request->plan, 'youtube')
    ]);

    session()->flash('success', 'New plan added successfully!');

    return Response::json('success', 200);
  }

  public function viewPlans(Request $request, $package_id)
  {
    $information['langs'] = Language::all();
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the plans of selected package
    $information['plans'] = PackagePlan::where('language_id', $language->id)
      ->where('package_id', $package_id)
      ->orderBy('id', 'desc')
      ->paginate(10);

    $package = Package::where('id', $package_id)->firstOrFail();
    $information['package'] = $package;

    if ($package->plan_type == 'daywise') {
      return view('backend.packages.daywise_plans', $information);
    } else if ($package->plan_type == 'timewise') {
      return view('backend.packages.timewise_plans', $information);
    }
  }

  public function updateDaywisePlan(Request $request)
  {
    $rules = [
      'day_number' => 'required',
      'edit_plan' => 'required'
    ];

    $messages = [];
    $messages['edit_plan.required'] = 'The plan feild is required.';

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $in = $request->all();
    $in['plan'] = Purifier::clean($request->edit_plan, 'youtube');

    PackagePlan::where('id', $request->plan_id)->first()->update($in);

    session()->flash('success', 'Plan updated successfully!');

    return 'success';
  }

  public function updateTimewisePlan(Request $request)
  {
    $rules = [
      'start_time' => 'required',
      'end_time' => 'required',
      'edit_plan' => 'required'
    ];
    $messages = [];
    $messages['edit_plan'] = 'The plan feild is required.';

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $in = $request->all();
    $in['plan'] = Purifier::clean($request->edit_plan, 'youtube');

    PackagePlan::where('id', $request->plan_id)->first()->update($in);

    session()->flash('success', 'Plan updated successfully!');

    return 'success';
  }

  public function deletePlan(Request $request)
  {
    PackagePlan::where('id', $request->plan_id)->first()->delete();

    session()->flash('success', 'Plan deleted successfully!');

    return redirect()->back();
  }

  public function bulkDeletePlan(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      PackagePlan::where('id', $id)->first()->delete();
    }

    session()->flash('success', 'Plans deleted successfully!');

    /**
     * this 'success' is returning for ajax call.
     * if return == 'success' then ajax will reload the page.
     */
    return 'success';
  }

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

    if (URL::current() == Route::is('admin.package_bookings.all_bookings')) {
      $bookings = PackageBooking::when($booking_number, function ($query, $booking_number) {
        return $query->where('booking_number', 'like', '%' . $booking_number . '%');
      })
        ->when($title, function ($query) use ($packageIds) {
          return $query->whereIn('package_id', $packageIds);
        })
        ->orderBy('id', 'desc')
        ->paginate(10);
    } else if (URL::current() == Route::is('admin.package_bookings.paid_bookings')) {
      $bookings = PackageBooking::when($booking_number, function ($query, $booking_number) {
        return $query->where('booking_number', 'like', '%' . $booking_number . '%');
      })
        ->when($title, function ($query) use ($packageIds) {
          return $query->whereIn('package_id', $packageIds);
        })
        ->where('payment_status', 1)
        ->orderBy('id', 'desc')
        ->paginate(10);
    } else if (URL::current() == Route::is('admin.package_bookings.unpaid_bookings')) {
      $bookings = PackageBooking::when($booking_number, function ($query, $booking_number) {
        return $query->where('booking_number', 'like', '%' . $booking_number . '%');
      })
        ->when($title, function ($query) use ($packageIds) {
          return $query->whereIn('package_id', $packageIds);
        })
        ->where('payment_status', 0)
        ->orderBy('id', 'desc')
        ->paginate(10);
    }

    return view('backend.packages.bookings', compact('bookings'));
  }

  public function updatePaymentStatus(Request $request)
  {
    $packageBooking = PackageBooking::where('id', $request->booking_id)->first();

    if ($request->payment_status == 1) {
      //calculate commission start
      if (!empty($packageBooking)) {
        if ($packageBooking->vendor_id != NULL) {
          $vendor_id = $packageBooking->vendor_id;
        } else {
          $vendor_id = NULL;
        }
      } else {
        $vendor_id = NULL;
      }

      //calculate commission
      $percent = Commission::select('package_booking_commission')->first();

      $commission = ($packageBooking->grand_total * $percent->package_booking_commission) / 100;

      //get vendor
      $vendor = Vendor::where('id', $packageBooking->vendor_id)->first();

      //add blance to admin revinue
      $earning = Earning::first();

      $earning->total_revenue = $earning->total_revenue + $packageBooking->grand_total;
      if ($vendor) {
        $earning->total_earning = $earning->total_earning + $commission;
      } else {
        $earning->total_earning = $earning->total_earning + $packageBooking->grand_total;
      }
      $earning->save();
      //store Balance  to vendor

      if ($vendor) {
        $pre_balance = $vendor->amount;
        $received_amount = $vendor->amount + ($packageBooking->grand_total - $commission);
        $vendor->amount = $received_amount;
        $vendor->save();
        $after_balance = $vendor->amount;

        $booking_received_amount = $packageBooking->grand_total - $commission;
      } else {
        $received_amount = NULL;
        $after_balance = NULL;
        $pre_balance = NULL;
        $booking_received_amount = NULL;
      }
      //calculate commission end

      $packageBooking->update([
        'payment_status' => 1,
        'comission' => $commission,
        'received_amount' => $booking_received_amount,
      ]);

      //store data to transcation table
      $data = [
        'transcation_id' => time(),
        'booking_id' => $packageBooking->id,
        'transcation_type' => 5,
        'user_id' => null,
        'vendor_id' => $vendor_id,
        'payment_status' => 1,
        'payment_method' => $packageBooking->payment_method,
        'grand_total' => $packageBooking->grand_total,
        'commission' => $packageBooking->comission,
        'pre_balance' => $pre_balance,
        'after_balance' => $after_balance,
        'gateway_type' => $packageBooking->gateway_type,
        'currency_symbol' => $packageBooking->currency_symbol,
        'currency_symbol_position' => $packageBooking->currency_symbol_position,
      ];
      store_transaction($data);
    } else {
      $packageBooking->update(['payment_status' => 0]);
      //calculate commission start
      if (!empty($packageBooking)) {
        if ($packageBooking->vendor_id != NULL) {
          $vendor_id = $packageBooking->vendor_id;
        } else {
          $vendor_id = NULL;
        }
      } else {
        $vendor_id = NULL;
      }
      //store data to transcation table
      $transcation = Transaction::where([['booking_id', $packageBooking->id], ['transcation_type', 5]])->first();
      $transcation->update(['payment_status' => 0]);

      //calculate commission
      $percent = Commission::select('package_booking_commission')->first();

      $commission = ($packageBooking->grand_total * $percent->package_booking_commission) / 100;

      //add blance to admin revinue
      $earning = Earning::first();
      $earning->total_revenue = $earning->total_revenue - $packageBooking->grand_total;
      $earning->total_earning = $earning->total_earning - $commission;
      $earning->save();
      //store Balance  to vendor
      $vendor = Vendor::where('id', $packageBooking->vendor_id)->first();
      if ($vendor) {
        $vendor->amount = $vendor->amount - ($packageBooking->grand_total - $commission);
        $vendor->save();
      }
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
    $packageTitle = $packageContentInfo->title;

    $packageCategoryInfo = $packageContentInfo->packageCategory()->first();

    if (!is_null($packageCategoryInfo)) {
      $packageCategoryName = $packageCategoryInfo->name;
    } else {
      $packageCategoryName = null;
    }

    return view(
      'backend.packages.booking_details',
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


  private function generateInvoice($bookingInfo)
  {
    $fileName = $bookingInfo->booking_number . '.pdf';
    $directory = public_path('assets/invoices/packages/');

    if (!file_exists($directory)) {
      @mkdir($directory, 0775, true);
    }

    $fileLocated = $directory . $fileName;

    PDF::loadView('frontend.pdf.package_booking', compact('bookingInfo'))->save($fileLocated);

    return $fileName;
  }

  private function sendMailForPaymentStatus($packageBooking, $status)
  {
    // first get the mail template information from db
    if ($status == 1) {
      $mailTemplate = MailTemplate::where('mail_type', 'payment_received')->firstOrFail();
    } else {
      $mailTemplate = MailTemplate::where('mail_type', 'payment_cancelled')->firstOrFail();
    }
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second get the website title & mail's smtp information from db
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    // replace template's curly-brace string with actual data
    $mailBody = str_replace('{customer_name}', $packageBooking->customer_name, $mailBody);
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
      $mail->addAddress($packageBooking->customer_email);

      // Attachments (Invoice)
      $mail->addAttachment(public_path('assets/invoices/packages/') . $packageBooking->invoice);

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
