<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use DB;

class HeroVideoController extends Controller
{
  public function videoVersion(Request $request)
  {
    $information['data'] = DB::table('basic_settings')->select('hero_video_link')
      ->first();

    return view('backend.home_page.hero_section.video_version', $information);
  }

  public function updateVideoInfo(Request $request)
  {
    $rule = [
      'hero_video_link' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $link = $request->hero_video_link;

    if (strpos($link, '&') != 0) {
      $link = substr($link, 0, strpos($link, '&'));
    }

    DB::table('basic_settings')->update(['hero_video_link' => $link]);

    session()->flash('success', 'Video info updated successfully!');

    return 'success';
  }
}
