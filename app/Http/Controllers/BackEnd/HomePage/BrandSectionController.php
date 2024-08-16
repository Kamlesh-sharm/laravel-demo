<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\Brand;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BrandSectionController extends Controller
{
  public function brandSection(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // also, get the brand info of that language from db
    $information['brands'] = Brand::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->get();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.home_page.brand_section.index', $information);
  }

  public function storeBrand(Request $request, $language)
  {
    $rules = [
      'brand_url' => 'required',
      'serial_number' => 'required'
    ];

    $brandImgURL = $request->brand_img;

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    $rules['brand_img'] = 'required';

    if ($request->hasFile('brand_img')) {
      $fileExtension = $request->file('brand_img')->getClientOriginalExtension();
      $rules['brand_img'] = [
        function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
          if (!in_array($fileExtension, $allowedExtensions)) {
            $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
          }
        }
      ];
    }


    $message = [
      'brand_img.required' => 'The brand image field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $lang = Language::where('code', $language)->first();
    $in = $request->all();

    if ($request->hasFile('brand_img')) {
      $filename = time() . '.' . $request->file('brand_img')->getClientOriginalExtension();
      $directory = public_path('assets/img/brands/');
      @mkdir($directory, 0775, true);
      $request->file('brand_img')->move($directory, $filename);
      $in['brand_img'] = $filename;
    }
    $in['language_id'] = $lang->id;

    Brand::create($in);

    session()->flash('success', 'New brand added successfully!');

    return 'success';
  }

  public function updateBrand(Request $request)
  {
    $rules = [
      'brand_url' => 'required',
      'serial_number' => 'required'
    ];

    $brandImgURL = $request->brand_img;

    if ($request->hasFile('brand_img')) {
      $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
      $fileExtension = $request->file('brand_img')->getClientOriginalExtension();

      $rules['brand_img'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
        if (!in_array($fileExtension, $allowedExtensions)) {
          $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
        }
      };
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $brand = Brand::where('id', $request->brand_id)->first();

    $in = $request->all();
    if ($request->hasFile('brand_img')) {
      $filename = time() . '.' . $request->file('brand_img')->getClientOriginalExtension();
      $directory = public_path('assets/img/brands/');
      @mkdir($directory, 0775, true);
      $request->file('brand_img')->move($directory, $filename);
      @unlink(public_path('assets/img/brands/') . $brand->brand_img);
      $in['brand_img'] = $filename;
    }

    $brand->update($in);

    session()->flash('success', 'Brand info updated successfully!');

    return 'success';
  }

  public function deleteBrand(Request $request)
  {
    $brand = Brand::where('id', $request->brand_id)->first();

    if (!is_null($brand->brand_img) && file_exists(public_path('assets/img/brands/') . $brand->brand_img)) {
      @unlink(public_path('assets/img/brands/') . $brand->brand_img);
    }

    $brand->delete();

    session()->flash('success', 'Brand deleted successfully!');

    return redirect()->back();
  }
}
