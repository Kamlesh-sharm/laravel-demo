<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\GalleryManagement\Gallery;
use App\Models\GalleryManagement\GalleryCategory;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
  use MiscellaneousTrait;

  public function gallery()
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['pageHeading'] = MiscellaneousTrait::getPageHeading($language);

    $queryResult['categories'] = GalleryCategory::where('language_id', $language->id)
      ->where('status', 1)
      ->orderBy('serial_number', 'asc')
      ->get();

    $queryResult['galleryInfos'] = Gallery::with('galleryCategory')
      ->where('language_id', $language->id)
      ->orderBy('serial_number', 'asc')
      ->get();

    return view('frontend.gallery', $queryResult);
  }
}
