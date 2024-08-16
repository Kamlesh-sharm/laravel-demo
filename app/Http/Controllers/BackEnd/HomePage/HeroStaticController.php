<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeroStaticRequest;
use App\Models\HomePage\HeroStatic;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HeroStaticController extends Controller
{
  public function staticVersion(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the static version info of that language from db
    $information['data'] = HeroStatic::where('language_id', $language->id)->first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.home_page.hero_section.static_version', $information);
  }

  public function updateStaticInfo(HeroStaticRequest $request, $language)
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

    $lang = Language::where('code', $language)->first();
    $data = HeroStatic::where('language_id', $lang->id)->first();

    $in = $request->all();

    if ($data == null) {
      if ($request->hasFile('img')) {
        $filename = time() . '.' . $request->file('img')->getClientOriginalExtension();
        $directory = public_path('assets/img/hero_static/');
        @mkdir($directory, 0775, true);
        $request->file('img')->move($directory, $filename);
        $in['img'] = $filename;
      }

      $in['language_id'] = $lang->id;

      HeroStatic::create($in);
    } else {
      if ($request->hasFile('img')) {
        @unlink(public_path('assets/img/hero_static/') . $data->img);
        $filename = time() . '.' . $request->file('img')->getClientOriginalExtension();
        $directory = public_path('assets/img/hero_static/');
        @mkdir($directory, 0775, true);
        $request->file('img')->move($directory, $filename);
        $in['img'] = $filename;
      }


      $data->update($in);
    }

    session()->flash('success', 'Static info updated successfully!');

    return redirect()->back();
  }
}
