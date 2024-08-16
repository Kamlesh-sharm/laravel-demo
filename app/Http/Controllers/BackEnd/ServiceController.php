<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\ServiceManagement\Service;
use App\Models\ServiceManagement\ServiceContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class ServiceController extends Controller
{
  public function services()
  {
    $languageId = Language::where('is_default', 1)->pluck('id')->first();

    $serviceContents = ServiceContent::with('service')
      ->where('language_id', '=', $languageId)
      ->orderBy('service_id', 'desc')
      ->get();

    return view('backend.services.index', compact('serviceContents'));
  }

  public function createService()
  {
    // get all the languages from db
    $information['languages'] = Language::all();

    return view('backend.services.create', $information);
  }

  public function storeService(Request $request)
  {
    $rules = [
      'service_icon' => 'required',
      'details_page_status' => 'required',
      'serial_number' => 'required'
    ];

    $languages = Language::all();

    foreach ($languages as $language) {
      $rules[$language->code . '_title'] = 'required|max:255';
      $rules[$language->code . '_summary'] = 'required';

      $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';

      $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

      $messages[$language->code . '_summary.required'] = 'The summary field is required for ' . $language->name . ' language';

      if ($request->details_page_status == 1) {
        $rules[$language->code . '_content'] = 'required|min:15';

        $messages[$language->code . '_content.required'] = 'The content field is required for ' . $language->name . ' language';

        $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language';
      }
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $service = new Service();
    $service->service_icon = $request->service_icon;
    $service->details_page_status = $request->details_page_status;
    $service->serial_number = $request->serial_number;
    $service->save();

    foreach ($languages as $language) {
      $serviceContent = new ServiceContent();
      $serviceContent->language_id = $language->id;
      $serviceContent->service_id = $service->id;
      $serviceContent->title = $request[$language->code . '_title'];
      $serviceContent->slug = createSlug($request[$language->code . '_title']);
      $serviceContent->summary = $request[$language->code . '_summary'];
      $serviceContent->content = $request->details_page_status == 1 ? Purifier::clean($request[$language->code . '_content'], 'youtube') : '';
      $serviceContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $serviceContent->meta_description = $request[$language->code . '_meta_description'];
      $serviceContent->save();
    }

    session()->flash('success', 'New service added successfully!');

    return 'success';
  }

  public function updateFeaturedService(Request $request)
  {
    $service = Service::where('id', $request->serviceId)->first();

    if ($request->is_featured == 1) {
      $service->update(['is_featured' => 1]);

      session()->flash('success', 'Service featured successfully!');
    } else {
      $service->update(['is_featured' => 0]);

      session()->flash('success', 'Service unfeatured successfully!');
    }

    return redirect()->back();
  }

  public function editService($id)
  {
    // get all the languages from db
    $information['languages'] = Language::all();

    $information['service'] = Service::where('id', $id)->firstOrFail();

    return view('backend.services.edit', $information);
  }

  public function updateService(Request $request, $id)
  {
    $rules = [
      'service_icon' => 'required',
      'details_page_status' => 'required',
      'serial_number' => 'required'
    ];

    $languages = Language::all();

    foreach ($languages as $language) {
      $rules[$language->code . '_title'] = 'required|max:255';
      $rules[$language->code . '_summary'] = 'required';

      $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';

      $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

      $messages[$language->code . '_summary.required'] = 'The summary field is required for ' . $language->name . ' language';

      if ($request->details_page_status == 1) {
        $rules[$language->code . '_content'] = 'required|min:15';

        $messages[$language->code . '_content.required'] = 'The content field is required for ' . $language->name . ' language';

        $messages[$language->code . '_content.min'] = 'The content field atleast have 15 characters for ' . $language->name . ' language';
      }
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $service = Service::where('id', $id)->first();

    $service->update([
      'service_icon' => $request->service_icon,
      'details_page_status' => $request->details_page_status,
      'serial_number' => $request->serial_number
    ]);

    foreach ($languages as $language) {
      $serviceContent = ServiceContent::where('service_id', $id)
        ->where('language_id', $language->id)
        ->first();

      $content = [
        'language_id' => $language->id,
        'service_id' => $id,
        'title' => $request[$language->code . '_title'],
        'slug' => createSlug($request[$language->code . '_title']),
        'summary' => $request[$language->code . '_summary'],
        'content' => $request->details_page_status == 1 ? Purifier::clean($request[$language->code . '_content'], 'youtube') : '',
        'meta_keywords' => $request[$language->code . '_meta_keywords'],
        'meta_description' => $request[$language->code . '_meta_description']
      ];

      if (!empty($serviceContent)) {
        $serviceContent->update($content);
      } else {
        ServiceContent::create($content);
      }
    }

    session()->flash('success', 'Service updated successfully!');

    return 'success';
  }

  public function deleteService(Request $request)
  {
    $service = Service::where('id', $request->service_id)->first();

    if ($service->serviceContent()->count() > 0) {
      $contents = $service->serviceContent()->get();

      foreach ($contents as $content) {
        $content->delete();
      }
    }

    $service->delete();

    session()->flash('success', 'Service deleted successfully!');

    return redirect()->back();
  }

  public function bulkDeleteService(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $service = Service::where('id', $id)->first();

      if ($service->serviceContent()->count() > 0) {
        $contents = $service->serviceContent()->get();

        foreach ($contents as $content) {
          $content->delete();
        }
      }

      $service->delete();
    }

    session()->flash('success', 'Services deleted successfully!');

    /**
     * this 'success' is returning for ajax call.
     * if return == 'success' then ajax will reload the page.
     */
    return 'success';
  }
}
