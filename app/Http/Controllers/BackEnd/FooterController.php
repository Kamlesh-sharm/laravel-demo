<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Footer\FooterQuickLink;
use App\Models\Footer\FooterText;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class FooterController extends Controller
{
  public function footerText(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the footer text info of that language from db
    $information['data'] = FooterText::where('language_id', $language->id)->first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.footer.text', $information);
  }

  public function updateFooterInfo(Request $request, $language)
  {
    $rules = [
      'about_company' => 'required',
      'copyright_text' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $lang = Language::where('code', $language)->firstOrFail();
    $data = FooterText::where('language_id', $lang->id)->first();

    if ($data == null) {
      FooterText::create($request->except('language_id', 'copyright_text') + [
        'language_id' => $lang->id,
        'copyright_text' => Purifier::clean($request->copyright_text, 'youtube')
      ]);
    } else {
      $data->update($request->except('copyright_text') + [
        'copyright_text' => Purifier::clean($request->copyright_text, 'youtube')
      ]);
    }

    session()->flash('success', 'Footer text info updated successfully!');

    return 'success';
  }


  public function quickLinks(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the footer quick link info of that language from db
    $information['links'] = FooterQuickLink::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->get();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.footer.quick_links', $information);
  }

  public function storeQuickLink(Request $request, $language)
  {
    $rules = [
      'title' => 'required',
      'url' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $lang = Language::where('code', $language)->firstOrFail();

    FooterQuickLink::create($request->except('language_id') + [
      'language_id' => $lang->id
    ]);

    session()->flash('success', 'New quick link added successfully!');

    return 'success';
  }

  public function updateQuickLink(Request $request)
  {
    $rules = [
      'title' => 'required',
      'url' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    FooterQuickLink::where('id', $request->link_id)->first()->update($request->all());

    session()->flash('success', 'Quick link updated successfully!');

    return 'success';
  }

  public function deleteQuickLink(Request $request)
  {
    FooterQuickLink::where('id', $request->link_id)->first()->delete();

    session()->flash('success', 'Quick link deleted successfully!');

    return redirect()->back();
  }
}
