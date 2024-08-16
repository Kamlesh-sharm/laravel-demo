<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\Testimonial;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
  public function createTestimonial(Request $request)
  {
    // first, get the language info from db
    $information['language'] = Language::where('code', $request->language)->firstOrFail();
    $information['langs'] = Language::get();

    return view('backend.home_page.testimonial_section.create', $information);
  }

  public function storeTestimonial(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'client_name' => 'required',
      'comment' => 'required',
      'serial_number' => 'required'
    ];

    $basicSettingsData = DB::table('basic_settings')->select('theme_version')
      ->where('uniqid', 12345)
      ->first();

    if ($basicSettingsData->theme_version == 'theme_two') {
      if (!$request->filled('client_image')) {
        $rules['client_image'] = 'required';
      }
    }

    $clientImgURL = $request->client_image;

    if ($request->hasFile('client_image')) {
      $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
      $fileExtension = $request->file('client_image')->getClientOriginalExtension();

      $rules['client_image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
        if (!in_array($fileExtension, $allowedExtensions)) {
          $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
        }
      };
    }

    if ($basicSettingsData->theme_version == 'theme_two') {
      $rules['client_designation'] = 'required';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    $in = $request->all();
    if ($request->hasFile('client_image')) {
      $filename = time() . '.' . $request->file('client_image')->getClientOriginalExtension();
      $directory = public_path('assets/img/testimonial_section/');
      @mkdir($directory, 0775, true);
      $request->file('client_image')->move($directory, $filename);
      $in['client_image'] = $filename;
    }

    Testimonial::create($in);

    session()->flash('success', 'New testimonial added successfully!');

    return redirect()->back();
  }

  public function editTestimonial(Request $request, $id)
  {
    // first, get the language info from db
    $information['language'] = Language::where('code', $request->language)->firstOrFail();

    $information['testimonialInfo'] = Testimonial::where('id', $id)->firstOrFail();

    return view('backend.home_page.testimonial_section.edit', $information);
  }

  public function updateTestimonial(Request $request, $id)
  {
    $rules = [
      'client_name' => 'required',
      'comment' => 'required',
      'serial_number' => 'required'
    ];

    $basicSettingsData = DB::table('basic_settings')->select('theme_version')
      ->where('uniqid', 12345)
      ->first();

    if ($request->hasFile('client_image')) {
      $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
      $fileExtension = $request->file('client_image')->getClientOriginalExtension();

      $rules['client_image'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
        if (!in_array($fileExtension, $allowedExtensions)) {
          $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
        }
      };
    }

    if ($basicSettingsData->theme_version == 'theme_two') {
      $rules['client_designation'] = 'required';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    $testimonial = Testimonial::where('id', $id)->first();
    $in = $request->all();

    if ($request->hasFile('client_image')) {
      $filename = time() . '.' . $request->file('client_image')->getClientOriginalExtension();
      $directory = public_path('assets/img/testimonial_section/');
      @mkdir($directory, 0775, true);
      $request->file('client_image')->move($directory, $filename);
      @unlink(public_path('assets/img/testimonial_section/') . $testimonial->client_image);
      $in['client_image'] = $filename;
    }

    $testimonial->update($in);

    session()->flash('success', 'Testimonial updated successfully!');

    return redirect()->back();
  }

  public function deleteTestimonial(Request $request)
  {
    $data = DB::table('basic_settings')->select('theme_version')
      ->where('uniqid', 12345)
      ->first();

    $testimonial = Testimonial::where('id', $request->testimonial_id)->first();

    if ($data->theme_version == 'theme_two') {
      if (
        !is_null($testimonial->client_image) &&
        file_exists(public_path('assets/img/testimonial_section/') . $testimonial->client_image)
      ) {
        @unlink(public_path('assets/img/testimonial_section/') . $testimonial->client_image);
      }
    }

    $testimonial->delete();

    session()->flash('success', 'Testimonial deleted successfully!');

    return redirect()->back();
  }
}
