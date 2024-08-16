<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageContent;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dynamicPage($slug)
    {
      $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

      $language = MiscellaneousTrait::getLanguage();

      $pageId = PageContent::where('slug', $slug)->firstOrFail()->page_id;
      $queryResult['details'] = Page::join('page_contents', 'pages.id', '=', 'page_contents.page_id')
        ->where('page_contents.language_id', $language->id)
        ->where('page_contents.page_id', $pageId)
        ->firstOrFail();

      return view('frontend.custom-page', $queryResult);
    }
}
