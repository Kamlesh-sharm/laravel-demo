<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\BlogManagement\Blog;
use App\Models\BlogManagement\BlogCategory;
use App\Models\BlogManagement\BlogContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class BlogController extends Controller
{
  public function blogCategories(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the blog categories of that language from db
    $information['blogCategories'] = BlogCategory::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->paginate(10);

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.blogs.categories', $information);
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

    $blogCategory = new BlogCategory();
    $in = $request->all();

    $blogCategory::create($in);

    session()->flash('success', 'New blog category added successfully!');

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

    $blogCategory = BlogCategory::where('id', $request->category_id)->firstOrFail();

    $blogCategory->update($request->all());

    session()->flash('success', 'Blog category updated successfully!');

    return 'success';
  }

  public function deleteCategory(Request $request)
  {
    $blogCategory = BlogCategory::where('id', $request->category_id)->firstOrFail();

    if ($blogCategory->blogContentList()->count() > 0) {
      session()->flash('warning', 'First delete all the blogs of this category!');

      return redirect()->back();
    }

    $blogCategory->delete();

    session()->flash('success', 'Blog category deleted successfully!');

    return redirect()->back();
  }

  public function bulkDeleteCategory(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $blogCategory = BlogCategory::where('id', $id)->first();

      if ($blogCategory->blogContentList()->count() > 0) {
        session()->flash('warning', 'First delete all the blogs of those category!');

        /**
         * this 'success' is returning for ajax call.
         * here, by returning the 'success' ajax will show the flash error message
         */
        return 'success';
      }

      $blogCategory->delete();
    }

    session()->flash('success', 'Blog categories deleted successfully!');

    /**
     * this 'success' is returning for ajax call.
     * if return == 'success' then ajax will reload the page.
     */
    return 'success';
  }


  public function blogs()
  {
    $languageId = Language::where('is_default', 1)->pluck('id')->first();

    $blogContents = BlogContent::with('blog')
      ->where('language_id', '=', $languageId)
      ->orderBy('blog_id', 'desc')
      ->get();

    return view('backend.blogs.blogs', compact('blogContents'));
  }

  public function createBlog()
  {
    // get all the languages from db
    $information['languages'] = Language::all();

    return view('backend.blogs.create_blog', $information);
  }

  public function storeBlog(Request $request)
  {
    $rules = ['serial_number' => 'required'];
    $imgURL = $request->blog_img;

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    $rules['blog_img'] = 'required';

    if ($request->hasFile('blog_img')) {
      $fileExtension =  $request->file('blog_img')->getClientOriginalExtension();
      $rules['blog_img'] = [
        function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
          if (!in_array($fileExtension, $allowedExtensions)) {
            $fail('Only .jpg, .jpeg, .png and .svg file is allowed for blog image.');
          }
        }
      ];
    }

    $messages = ['blog_img.required' => 'The blog image field is required.'];

    $languages = Language::all();

    foreach ($languages as $language) {
      $rules[$language->code . '_title'] = 'required|max:255';
      $rules[$language->code . '_category'] = 'required';
      $rules[$language->code . '_content'] = 'required|min:15';

      $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';

      $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

      $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language';

      $messages[$language->code . '_content.required'] = 'The content field is required for ' . $language->name . ' language';

      $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $blog = new Blog();

    if ($request->hasFile('blog_img')) {
      $filename = time() . '.' . $request->file('blog_img')->getClientOriginalExtension();
      $directory = public_path('assets/img/blogs/');
      @mkdir($directory, 0775, true);
      $request->file('blog_img')->move($directory, $filename);
      $blog->blog_img = $filename;
    }

    $blog->serial_number = $request->serial_number;
    $blog->save();

    foreach ($languages as $language) {
      $blogContent = new BlogContent();
      $blogContent->language_id = $language->id;
      $blogContent->blog_category_id = $request[$language->code . '_category'];
      $blogContent->blog_id = $blog->id;
      $blogContent->title = $request[$language->code . '_title'];
      $blogContent->slug = createSlug($request[$language->code . '_title']);
      $blogContent->content = Purifier::clean($request[$language->code . '_content'], 'youtube');
      $blogContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $blogContent->meta_description = $request[$language->code . '_meta_description'];
      $blogContent->save();
    }

    session()->flash('success', 'New blog added successfully!');

    return 'success';
  }

  public function editBlog($id)
  {
    // get all the languages from db
    $information['languages'] = Language::all();

    $information['blog'] = Blog::where('id', $id)->firstOrFail();

    return view('backend.blogs.edit_blog', $information);
  }

  public function updateBlog(Request $request, $id)
  {
    $rules = ['serial_number' => 'required'];

    $blogImgURL = $request->blog_img;

    if ($request->hasFile('blog_img')) {
      $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
      $fileExtension = $request->file('blog_img')->getClientOriginalExtension();

      $rules['blog_img'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
        if (!in_array($fileExtension, $allowedExtensions)) {
          $fail('Only .jpg, .jpeg, .png and .svg file is allowed for blog image.');
        }
      };
    }

    $languages = Language::all();

    foreach ($languages as $language) {
      $rules[$language->code . '_title'] = 'required|max:255';
      $rules[$language->code . '_category'] = 'required';
      $rules[$language->code . '_content'] = 'required|min:15';

      $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';

      $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

      $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language';

      $messages[$language->code . '_content.required'] = 'The content field is required for ' . $language->name . ' language';

      $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $blog = Blog::where('id', $id)->first();

    $in = $request->all();

    if ($request->hasFile('blog_img')) {
      $filename = time() . '.' . $request->file('blog_img')->getClientOriginalExtension();
      $directory = public_path('assets/img/blogs/');
      @mkdir($directory, 0775, true);
      $request->file('blog_img')->move($directory, $filename);
      @unlink(public_path('assets/img/blogs/') . $blog->blog_img);
      $in['blog_img'] = $filename;
    }
    $in['serial_number'] = $request->serial_number;

    $blog->update($in);

    foreach ($languages as $language) {
      $blogContent = BlogContent::where('blog_id', $id)
        ->where('language_id', $language->id)
        ->first();

      $content = [
        'language_id' => $language->id,
        'blog_id' => $id,
        'blog_category_id' => $request[$language->code . '_category'],
        'title' => $request[$language->code . '_title'],
        'slug' => createSlug($request[$language->code . '_title']),
        'content' => Purifier::clean($request[$language->code . '_content'], 'youtube'),
        'meta_keywords' => $request[$language->code . '_meta_keywords'],
        'meta_description' => $request[$language->code . '_meta_description']
      ];

      if (!empty($blogContent)) {
        $blogContent->update($content);
      } else {
        BlogContent::create($content);
      }
    }

    session()->flash('success', 'Blog updated successfully!');

    return 'success';
  }

  public function deleteBlog(Request $request)
  {
    $blog = Blog::where('id', $request->blog_id)->first();

    if ($blog->blogContent()->count() > 0) {
      $contents = $blog->blogContent()->get();

      foreach ($contents as $content) {
        $content->delete();
      }
    }

    if (!is_null($blog->blog_img) && file_exists(public_path('assets/img/blogs/') . $blog->blog_img)) {
      @unlink(public_path('assets/img/blogs/') . $blog->blog_img);
    }

    $blog->delete();

    session()->flash('success', 'Blog deleted successfully!');

    return redirect()->back();
  }

  public function bulkDeleteBlog(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $blog = Blog::where('id', $id)->first();

      if ($blog->blogContent()->count() > 0) {
        $contents = $blog->blogContent()->get();

        foreach ($contents as $content) {
          $content->delete();
        }
      }

      if (!is_null($blog->blog_img) && file_exists(public_path('assets/img/blogs/') . $blog->blog_img)) {
        @unlink(public_path('assets/img/blogs/') . $blog->blog_img);
      }

      $blog->delete();
    }

    session()->flash('success', 'Blogs deleted successfully!');

    /**
     * this 'success' is returning for ajax call.
     * if return == 'success' then ajax will reload the page.
     */
    return 'success';
  }
}
