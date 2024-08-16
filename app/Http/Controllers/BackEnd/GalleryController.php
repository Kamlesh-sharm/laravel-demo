<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\GalleryManagement\Gallery;
use App\Models\GalleryManagement\GalleryCategory;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
  public function categories(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the gallery categories of that language from db
    $information['galleryCategories'] = GalleryCategory::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->paginate(10);

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.gallery.categories', $information);
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
    GalleryCategory::create($in);

    session()->flash('success', 'New gallery category added successfully!');

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

    GalleryCategory::where('id', $request->category_id)->first()->update($request->all());

    session()->flash('success', 'Gallery category updated successfully!');

    return 'success';
  }

  public function deleteCategory(Request $request)
  {
    $galleryCategory = GalleryCategory::where('id', $request->category_id)->first();

    if ($galleryCategory->galleryImgList()->count() > 0) {
      session()->flash('warning', 'First delete all the images of this category!');

      return redirect()->back();
    }

    $galleryCategory->delete();

    session()->flash('success', 'Gallery category deleted successfully!');

    return redirect()->back();
  }

  public function bulkDeleteCategory(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $galleryCategory = GalleryCategory::where('id', $id)->first();

      if ($galleryCategory->galleryImgList()->count() > 0) {
        session()->flash('warning', 'First delete all the images of those category!');

        /**
         * this 'success' is returning for ajax call.
         * if return == 'success' then ajax will reload the page.
         */
        return 'success';
      }

      $galleryCategory->delete();
    }

    session()->flash('success', 'Gallery categories deleted successfully!');

    /**
     * this 'success' is returning for ajax call.
     * if return == 'success' then ajax will reload the page.
     */
    return 'success';
  }


  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // get all the gallery categories of that language from db
    $information['categories'] = GalleryCategory::where('language_id', $language->id)
      ->where('status', 1)
      ->orderBy('serial_number', 'asc')
      ->get();

    // then, get the gallery images of that language from db
    $information['galleryInfos'] = Gallery::with('galleryCategory')
      ->where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->get();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.gallery.index', $information);
  }

  public function storeInfo(Request $request, $language)
  {
    $rules = [
      'gallery_category_id' => 'required',
      'title' => 'required',
      'serial_number' => 'required',
      'gallery_img' => 'required'
    ];

    $galleryImgURL = $request->gallery_img;

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    if ($request->hasFile('gallery_img')) {
      $fileExtension = $request->file('gallery_img')->getClientOriginalExtension();
      $rules['gallery_img'] = [
        function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
          if (!in_array($fileExtension, $allowedExtensions)) {
            $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
          }
        }
      ];
    }


    $message = [
      'gallery_img.required' => 'The gallery image field is required.',
      'gallery_category_id.required' => 'The category field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $language = Language::where('code', $language)->firstOrFail();

    $in = $request->all();
    if ($request->hasFile('gallery_img')) {
      $filename = time() . '.' . $request->file('gallery_img')->getClientOriginalExtension();
      $directory = public_path('assets/img/gallery/');
      @mkdir($directory, 0775, true);
      $request->file('gallery_img')->move($directory, $filename);
      $in['gallery_img'] = $filename;
    }
    $in['language_id'] = $language->id;

    Gallery::create($in);

    session()->flash('success', 'Gallery info added successfully!');

    return 'success';
  }

  public function updateInfo(Request $request)
  {
    $rules = [
      'gallery_category_id' => 'required',
      'title' => 'required',
      'serial_number' => 'required'
    ];

    $galleryImgURL = $request->gallery_img;

    if ($request->hasFile('gallery_img')) {
      $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
      $fileExtension = $request->file('gallery_img')->getClientOriginalExtension();

      $rules['gallery_img'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
        if (!in_array($fileExtension, $allowedExtensions)) {
          $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
        }
      };
    }

    $message = [
      'gallery_category_id.required' => 'The category field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $info = Gallery::where('id', $request->gallery_id)->firstOrFail();

    $in = $request->all();
    if ($request->hasFile('gallery_img')) {
      $filename = time() . '.' . $request->file('gallery_img')->getClientOriginalExtension();
      $directory = public_path('assets/img/gallery/');
      @mkdir($directory, 0775, true);
      $request->file('gallery_img')->move($directory, $filename);
      @unlink(public_path('assets/img/gallery/') . $info->gallery_img);
      $in['gallery_img'] = $filename;
    }

    $info->update($in);

    session()->flash('success', 'Gallery info updated successfully!');

    return 'success';
  }

  public function deleteInfo(Request $request)
  {
    $info = Gallery::where('id', $request->gallery_id)->firstOrFail();

    if (!is_null($info->gallery_img) && file_exists(public_path('assets/img/gallery/') . $info->gallery_img)) {
      @unlink(public_path('assets/img/gallery/') . $info->gallery_img);
    }

    $info->delete();

    session()->flash('success', 'Gallery info deleted successfully!');

    return redirect()->back();
  }

  public function bulkDeleteInfo(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $info = Gallery::where('id', $id)->first();

      if (!is_null($info->gallery_img) && file_exists(public_path('assets/img/gallery/') . $info->gallery_img)) {
        @unlink(public_path('assets/img/gallery/') . $info->gallery_img);
      }

      $info->delete();
    }

    session()->flash('success', 'Gallery infos deleted successfully!');

    /**
     * this 'success' is returning for ajax call.
     * if return == 'success' then ajax will reload the page.
     */
    return 'success';
  }
}
