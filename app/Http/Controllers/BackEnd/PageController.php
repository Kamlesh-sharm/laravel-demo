<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Language;
use App\Models\PageContent;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class PageController extends Controller
{
    public function index()
    {
        $data['languages'] = Language::all();
        $data['pages'] = Page::join('page_contents', 'page_contents.page_id', '=', 'pages.id')
            ->join('languages', 'languages.id', '=', 'page_contents.language_id')
            ->select('page_contents.name', 'pages.id', 'pages.serial_number as serial_number')
            ->where('languages.is_default', 1)->orderBy('pages.id', 'DESC')->get();

        return view('backend.page.index', $data);
    }

    public function create()
    {
        $data['languages'] = Language::all();
        return view('backend.page.create', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::all();

        $rules = [
            'serial_number' => 'required|integer',
        ];

        foreach ($languages as $key => $language) {
            $slug = createSlug($request[$language->code . "_name"]);
            $rules[$language->code . '_name'] = [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($slug, $language) {
                    $pages = PageContent::where('language_id', $language->id)->get();
                    foreach ($pages as $key => $page) {
                        if (strtolower($slug) == strtolower($page->slug)) {
                            $fail('Name field must be unique (for ' . $language->name . ' Language)');
                        }
                    }
                }
            ];
            $rules[$language->code . '_body'] = 'required|min:15';

            $messages[$language->code . '_name.required'] = 'Name is required (for ' . $language->name . ' Language)';
            $messages[$language->code . '_name.max'] = 'Name cannot contain more than 255 characters (for ' . $language->name . ' Language)';
            $messages[$language->code . '_body.required'] = 'Body is required (for ' . $language->name . ' Language)';
            $messages[$language->code . '_body.min'] = 'Body is required (for ' . $language->name . ' Language)';
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $page = new Page;
        $page->serial_number = $request->serial_number;
        $page->save();

        foreach ($languages as $key => $language) {
            $pc = new PageContent;
            $pc->page_id = $page->id;
            $pc->language_id = $language->id;
            $pc->name = $request[$language->code . "_name"];
            $pc->slug = createSlug($request[$language->code . "_name"]);
            $pc->body = Purifier::clean($request[$language->code . "_body"], 'youtube');
            $pc->meta_keywords = $request[$language->code . "_meta_keywords"];
            $pc->meta_description = $request[$language->code . "_meta_description"];
            $pc->save();
        }

        Session::flash('success', 'Page created successfully!');
        return "success";
    }

    public function edit($pageID)
    {
        $data['languages'] = Language::all();
        $data['page'] = Page::where('id', $pageID)->firstOrFail();
        return view('backend.page.edit', $data);
    }

    public function update(Request $request)
    {
        $languages = Language::all();
        $pageID = $request->pageid;

        $rules = [
            'serial_number' => 'required|integer',
        ];

        foreach ($languages as $key => $language) {
            $slug = createSlug($request[$language->code . "_name"]);
            $rules[$language->code . '_name'] = [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($slug, $pageID, $language) {
                    $pages = PageContent::where('language_id', $language->id)->get();
                    foreach ($pages as $key => $page) {
                        if ($page->page_id != $pageID && strtolower($slug) == strtolower($page->slug)) {
                            $fail('The name field must be unique (for ' . $language->name . ' Language)');
                        }
                    }
                }
            ];
            $rules[$language->code . '_body'] = 'required|min:15';

            $messages[$language->code . '_name.required'] = 'Name is required (for ' . $language->name . ' Language)';
            $messages[$language->code . '_name.max'] = 'Name cannot contain more than 255 characters (for ' . $language->name . ' Language)';
            $messages[$language->code . '_body.required'] = 'Body is required (for ' . $language->name . ' Language)';
            $messages[$language->code . '_body.min'] = 'Body is required (for ' . $language->name . ' Language)';
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }

        $page = Page::where('id', $pageID)->first();
        $page->serial_number = $request->serial_number;
        $page->save();


        foreach ($languages as $key => $language) {
            $pc = PageContent::where('page_id', $page->id)->where('language_id', $language->id)->first();

            if (empty($pc)) {
                $pc = new PageContent;
                $pc->language_id = $language->id;
                $pc->page_id = $page->id;
            }
            $pc->name = $request[$language->code . "_name"];
            $pc->slug = createSlug($request[$language->code . "_name"]);
            $pc->body = Purifier::clean($request[$language->code . "_body"], 'youtube');
            $pc->meta_keywords = $request[$language->code . "_meta_keywords"];
            $pc->meta_description = $request[$language->code . "_meta_description"];
            $pc->save();
        }

        Session::flash('success', 'Page updated successfully!');
        return "success";
    }

    public function delete(Request $request)
    {
        $pageID = $request->pageid;
        $page = Page::where('id', $pageID)->first();

        if ($page->page_contents()->count() > 0) {
            foreach ($page->page_contents as $key => $pc) {
                $pc->delete();
            }
        }

        $page->delete();
        Session::flash('success', 'Page deleted successfully!');
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $page = Page::where('id', $id)->first();

            if ($page->page_contents()->count() > 0) {
                foreach ($page->page_contents as $key => $pc) {
                    $pc->delete();
                }
            }

            $page->delete();
        }

        Session::flash('success', 'Pages deleted successfully!');
        return "success";
    }
}
