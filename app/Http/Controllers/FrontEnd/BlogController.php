<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BlogManagement\BlogContent;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
  use MiscellaneousTrait;

  public function blogs(Request $request)
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['pageHeading'] = MiscellaneousTrait::getPageHeading($language);

    $blog_name = $category_id = null;

    if ($request->filled('term')) {
      $blog_name = $request->term;
    }

    if ($request->filled('category')) {
      $category_id = $request->category;
    }

    $queryResult['blogInfos'] = DB::table('blogs')
      ->join('blog_contents', 'blogs.id', '=', 'blog_contents.blog_id')
      ->where('blog_contents.language_id', '=', $language->id)
      ->when($blog_name, function ($query, $blog_name) {
        return $query->where('title', 'like', '%' . $blog_name . '%');
      })->when($category_id, function ($query, $category_id) {
        return $query->where('blog_category_id', '=', $category_id);
      })->orderBy('blogs.serial_number', 'asc')
      ->paginate(3);

    $queryResult['recentBlogs'] = $this->getRecentBlogs();

    $queryResult['blogCategories'] = $this->getBlogCategories();

    return view('frontend.blogs.blogs', $queryResult);
  }

  public function blogDetails($id)
  {
    $queryResult['breadcrumbInfo'] = MiscellaneousTrait::getBreadcrumb();

    $language = MiscellaneousTrait::getLanguage();

    $queryResult['details'] = BlogContent::with('blog')
      ->where('language_id', $language->id)
      ->where('blog_id', $id)
      ->firstOrFail();

    $queryResult['recentBlogs'] = $this->getRecentBlogs();

    $queryResult['blogCategories'] = $this->getBlogCategories();

    return view('frontend.blogs.blog_details', $queryResult);
  }
}
