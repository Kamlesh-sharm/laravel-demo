<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BlogManagement\BlogContent;
use App\Models\FAQ;
use App\Models\HomePage\Brand;
use App\Models\HomePage\Facility;
use App\Models\HomePage\HeroSlider;
use App\Models\HomePage\IntroCountInfo;
use App\Models\HomePage\IntroSection;
use App\Models\HomePage\SectionHeading;
use App\Models\HomePage\Testimonial;
use App\Models\PackageManagement\PackageContent;
use App\Models\RoomManagement\Room;
use App\Models\RoomManagement\RoomContent;
use App\Models\ServiceManagement\ServiceContent;
use App\Models\Subscriber;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
  use MiscellaneousTrait;

  public function index()
  {

    $basicSettings = DB::table('basic_settings')->select('theme_version')
      ->where('uniqid', 12345)
      ->first();

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['sliders'] = HeroSlider::where('language_id', $language->id)
      ->orderBy('serial_number', 'asc')
      ->get();

    $queryResult['numOfBed'] = Room::where('status', 1)->max('bed');

    $queryResult['numOfBath'] = Room::where('status', 1)->max('bath');

    $queryResult['numOfGuest'] = Room::where('status', 1)->max('max_guests');

    $queryResult['intro'] = IntroSection::where('language_id', $language->id)->first();

    $queryResult['counterInfos'] = IntroCountInfo::where('language_id', $language->id)
      ->orderBy('serial_number', 'asc')
      ->get();

    $queryResult['secHeading'] = SectionHeading::where('language_id', $language->id)->first();

    $queryResult['roomInfos'] = RoomContent::with(['room' => function ($query) {
      $query->status()->where('is_featured', 1);
    }])->where('language_id', $language->id)
      ->get();

    // check whether featured room available or not (start)
    $hotelRooms = $queryResult['roomInfos'];

    $hasRoom = false;

    foreach ($hotelRooms as $hotelRoom) {
      if (!is_null($hotelRoom->room)) {
        $hasRoom = true;
      }
    }

    $queryResult['roomFlag'] = ($hasRoom === true) ? 1 : 0;
    // check whether featured room available or not (end)

    $queryResult['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

    $queryResult['serviceInfos'] = ServiceContent::with(['service' => function ($query) {
      $query->where('is_featured', 1);
    }])->where('language_id', $language->id)
      ->get();

    $queryResult['numOfBed'] = Room::where('status', 1)->max('bed');
    $queryResult['numOfBath'] = Room::where('status', 1)->max('bath');

    // check whether featured service available or not (start)
    $hotelServices = $queryResult['serviceInfos'];

    $hasService = false;

    foreach ($hotelServices as $hotelService) {
      if (!is_null($hotelService->service)) {
        $hasService = true;
      }
    }

    $queryResult['serviceFlag'] = ($hasService === true) ? 1 : 0;
    // check whether featured service available or not (end)

    $queryResult['packageInfos'] = PackageContent::with(['package' => function ($query) {
      $query->where('is_featured', 1);
    }])->where('language_id', $language->id)
      ->get();

    // check whether featured package available or not (start)
    $tourPackages = $queryResult['packageInfos'];

    $hasPackage = false;

    foreach ($tourPackages as $tourPackage) {
      if (!is_null($tourPackage->package)) {
        $hasPackage = true;
      }
    }

    $queryResult['packageFlag'] = ($hasPackage === true) ? 1 : 0;
    // check whether featured package available or not (end)

    $queryResult['facilities'] = Facility::where('language_id', $language->id)->get();

    $queryResult['testimonials'] = Testimonial::where('language_id', $language->id)
      ->orderBy('serial_number', 'asc')
      ->get();

    $queryResult['brands'] = Brand::where('language_id', $language->id)
      ->orderBy('serial_number', 'asc')
      ->get();

    $queryResult['faqs'] = FAQ::where('language_id', $language->id)
      ->orderby('serial_number', 'asc')
      ->get();

    $queryResult['blogInfos'] = BlogContent::with('blog')
      ->where('language_id', $language->id)
      ->orderBy('blog_id', 'desc')
      ->limit(3)
      ->get();

    if ($basicSettings->theme_version == 'theme_one') {
      return view('frontend.home.index_one', $queryResult);
    } else if ($basicSettings->theme_version == 'theme_two') {
      return view('frontend.home.index_two', $queryResult);
    }
  }

  public function about()
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();
    $language = MiscellaneousTrait::getLanguage();
    $queryResult['intro'] = IntroSection::where('language_id', $language->id)->first();

    $queryResult['counterInfos'] = IntroCountInfo::where('language_id', $language->id)
      ->orderBy('serial_number', 'asc')
      ->get();

    $queryResult['pageHeading'] = MiscellaneousTrait::getPageHeading($language);
    $queryResult['secHeading'] = SectionHeading::where('language_id', $language->id)->first();

    $queryResult['serviceInfos'] = ServiceContent::with(['service' => function ($query) {
      $query->where('is_featured', 1);
    }])->where('language_id', $language->id)
      ->get();
    $queryResult['packageInfos'] = PackageContent::with(['package' => function ($query) {
      $query->where('is_featured', 1);
    }])->where('language_id', $language->id)
      ->get();

    // check whether featured package available or not (start)
    $tourPackages = $queryResult['packageInfos'];

    $hasPackage = false;

    foreach ($tourPackages as $tourPackage) {
      if (!is_null($tourPackage->package)) {
        $hasPackage = true;
      }
    }

    $queryResult['packageFlag'] = ($hasPackage === true) ? 1 : 0;

    $queryResult['testimonials'] = Testimonial::where('language_id', $language->id)
      ->orderBy('serial_number', 'asc')
      ->get();

    $queryResult['brands'] = Brand::where('language_id', $language->id)
      ->orderBy('serial_number', 'asc')
      ->get();



    $queryResult['serviceInfos'] = ServiceContent::with(['service' => function ($query) {
      $query->where('is_featured', 1);
    }])->where('language_id', $language->id)
      ->get();

    // check whether featured service available or not (start)
    $hotelServices = $queryResult['serviceInfos'];

    $hasService = false;

    foreach ($hotelServices as $hotelService) {
      if (!is_null($hotelService->service)) {
        $hasService = true;
      }
    }

    $queryResult['serviceFlag'] = ($hasService === true) ? 1 : 0;

    return view('frontend.about', $queryResult);
  }

  public function subscribe(Request $request)
  {
    $rules = [
      'email' => 'required|email|unique:subscribers'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    }

    $subsc = new Subscriber;
    $subsc->email = $request->email;
    $subsc->save();

    return "success";
  }
}
