<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\HomePage\SectionHeading;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;

class FAQController extends Controller
{
  use MiscellaneousTrait;

  public function faqs()
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['pageHeading'] = MiscellaneousTrait::getPageHeading($language);

    $queryResult['secHeading'] = SectionHeading::where('language_id', $language->id)
      ->select('faq_section_title', 'faq_section_subtitle', 'faq_section_image')
      ->first();

    $queryResult['faqs'] = FAQ::where('language_id', $language->id)
      ->orderby('serial_number', 'asc')
      ->get();

    return view('frontend.faqs', $queryResult);
  }
}
