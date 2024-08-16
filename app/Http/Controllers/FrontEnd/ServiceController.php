<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\HomePage\SectionHeading;
use App\Models\ServiceManagement\ServiceContent;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
  use MiscellaneousTrait;

  public function services()
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['pageHeading'] = MiscellaneousTrait::getPageHeading($language);

    $queryResult['secHeading'] = SectionHeading::where('language_id', $language->id)
      ->select('service_section_title', 'service_section_subtitle')
      ->first();

    $queryResult['serviceInfos'] = DB::table('services')
      ->join('service_contents', 'services.id', '=', 'service_contents.service_id')
      ->where('service_contents.language_id', '=', $language->id)
      ->orderBy('services.serial_number', 'asc')
      ->get();

    return view('frontend.services.services', $queryResult);
  }

  public function serviceDetails($id)
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['details'] = ServiceContent::with('service')
      ->where('language_id', $language->id)
      ->where('service_id', $id)
      ->firstOrFail();

    $queryResult['moreServices'] = ServiceContent::with('service')->where('language_id', $language->id)
    ->where('service_id', '!=', $id)
    ->get();

    return view('frontend.services.service_details', $queryResult);
  }
}
