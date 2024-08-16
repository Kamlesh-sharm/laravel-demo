<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeroSliderRequest;
use App\Models\HomePage\HeroSlider;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HeroSliderController extends Controller
{
  public function sliderVersion(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the slider version info of that language from db
    $information['sliders'] = HeroSlider::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->get();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.home_page.hero_section.slider_version', $information);
  }

  public function createSlider(Request $request)
  {
    // get the language info from db
    $language = Language::where('code', $request->language)->first();
    $information['language'] = $language;
    $information['langs'] = Language::get();

    return view('backend.home_page.hero_section.create_slider', $information);
  }

  public function storeSliderInfo(HeroSliderRequest $request)
  {
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    $imgURL = $request->img;

    $rules['img'] = 'required';

    if ($request->hasFile('img')) {
      $fileExtension = $request->file('img')->getClientOriginalExtension();
      $rules['img'] = [
        function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
          if (!in_array($fileExtension, $allowedExtensions)) {
            $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
          }
        }
      ];
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }
    $in = $request->all();

    // set a name for the image and store it to local storage
    if ($request->hasFile('img')) {
      $filename = time() . '.' . $request->file('img')->getClientOriginalExtension();
      $directory = public_path('assets/img/hero_slider/');
      @mkdir($directory, 0775, true);
      $request->file('img')->move($directory, $filename);
      $in['img'] = $filename;
    }

    HeroSlider::create($in);

    session()->flash('success', 'New slider added successfully!');

    return redirect()->back();
  }

  public function editSlider(Request $request, $id)
  {
    // get the language info from db
    $language = Language::where('code', $request->language)->first();
    $information['language'] = $language;

    // get the slider info from db for update
    $information['slider'] = HeroSlider::where('id', $id)->first();

    return view('backend.home_page.hero_section.edit_slider', $information);
  }

  public function updateSliderInfo(HeroSliderRequest $request, $id)
  {
    $imgURL = $request->img;

    if ($request->hasFile('img')) {
      $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
      $fileExtension = $request->file('img')->getClientOriginalExtension();

      $rule = [
        'img' => function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
          if (!in_array($fileExtension, $allowedExtensions)) {
            $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
          }
        }
      ];

      $validator = Validator::make($request->all(), $rule);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator);
      }
    }

    $slider = HeroSlider::where('id', $id)->first();
    $in = $request->all();


    if ($request->hasFile('img')) {
      $filename = time() . '.' . $request->file('img')->getClientOriginalExtension();
      $directory = public_path('assets/img/hero_slider/');
      @mkdir($directory, 0775, true);
      $request->file('img')->move($directory, $filename);
      @unlink(public_path('assets/img/hero_slider/') . $slider->img);
      $in['img'] = $filename;
    }

    $slider->update($in);

    session()->flash('success', 'Slider info updated successfully!');

    return redirect()->back();
  }

  public function deleteSlider(Request $request)
  {
    $slider = HeroSlider::where('id', $request->slider_id)->first();

    if (
      !is_null($slider->img) &&
      file_exists(public_path('assets/img/hero_slider/') . $slider->img)
    ) {
      @unlink(public_path('assets/img/hero_slider/') . $slider->img);
    }

    $slider->delete();

    session()->flash('success', 'Slider deleted successfully!');

    return redirect()->back();
  }
}
