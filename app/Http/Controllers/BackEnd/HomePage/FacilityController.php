<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\Facility;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FacilityController extends Controller
{
  public function createFacility(Request $request)
  {
    // first, get the language info from db
    $information['language'] = Language::where('code', $request->language)->first();

    return view('backend.home_page.facility_section.create', $information);
  }

  public function storeFacility(Request $request, $language)
  {
    $rules = [
      'facility_icon' => 'required',
      'facility_title' => 'required',
      'facility_text' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $lang = Language::where('code', $language)->first();

    Facility::create($request->except('language_id') + [
      'language_id' => $lang->id
    ]);

    session()->flash('success', 'New facility added successfully!');

    return 'success';
  }

  public function editFacility(Request $request, $id)
  {
    // first, get the language info from db
    $information['language'] = Language::where('code', $request->language)->first();

    $information['facilityInfo'] = Facility::where('id', $id)->first();

    return view('backend.home_page.facility_section.edit', $information);
  }

  public function updateFacility(Request $request, $id)
  {
    $rules = [
      'facility_icon' => 'required',
      'facility_title' => 'required',
      'facility_text' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    Facility::where('id', $id)->first()->update($request->all());

    session()->flash('success', 'Facility updated successfully!');

    return 'success';
  }

  public function deleteFacility(Request $request)
  {
    Facility::where('id', $request->facilityInfo_id)->first()->delete();

    session()->flash('success', 'Facility deleted successfully!');

    return redirect()->back();
  }
}
