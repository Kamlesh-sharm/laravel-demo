<?php

namespace App\Http\Controllers;

use App\Models\BlogManagement\BlogCategory;
use App\Models\BlogManagement\BlogContent;
use App\Traits\MiscellaneousTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests, MiscellaneousTrait;

  public function getRecentBlogs()
  {
    $language = MiscellaneousTrait::getLanguage();

    $recentBlogs = BlogContent::with('blog')->where('language_id', $language->id)
      ->orderBy('blog_id', 'desc')
      ->limit(5)
      ->get();

    return $recentBlogs;
  }

  public function getBlogCategories()
  {
    $language = MiscellaneousTrait::getLanguage();

    $blogCategories = BlogCategory::where('language_id', $language->id)
      ->where('status', 1)
      ->orderBy('serial_number', 'asc')
      ->get();

    return $blogCategories;
  }

  public function changeLanguage(Request $request)
  {
    // put the selected language in session
    $langCode = $request['lang_code'];

    session()->put('currentLocaleCode', $langCode);

    return redirect()->back();
  }
}
