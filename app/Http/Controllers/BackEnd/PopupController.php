<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\Language;
use App\Models\Popup;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PopupController extends Controller
{
    public function index(Request $request)
    {
        $data['langs'] = Language::all();
        $lang = Language::where('code', $request->language)->firstOrFail();
        $lang_id = $lang->id;
        $data['popups'] = Popup::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        $data['lang'] = $lang;
        return view('backend.popups.index', $data);
    }

    public function types()
    {
        return view('backend.popups.types');
    }

    public function create()
    {
        $data['langs'] = Language::all();
        return view('backend.popups.create', $data);
    }

    public function edit($id)
    {
        $data['popup'] = Popup::where('id', $id)->firstOrFail();
        $data['language'] = Language::where('id', $data['popup']->language_id)->first();
        return view('backend.popups.edit', $data);
    }

    public function store(Request $request)
    {
        $type = $request->type;

        $messages = [
            'language_id.required' => 'The language field is required'
        ];

        $rules = [
            'name' => 'required',
            'language_id' => 'required',
            'serial_number' => 'required|integer',
            'delay' => 'required|integer',
        ];

        if ($type == 1 || $type == 4 || $type == 5 || $type == 7) {
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
            $rules['image'] = 'required';
            if ($request->hasFile('image')) {
                $extImage = $request->file('image')->getClientOriginalExtension();
                $rules['image'] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }
        }

        if ($type == 2 || $type == 3 || $type == 6) {
            $allowedBgExts = array('jpg', 'png', 'jpeg', 'svg');
            $rules['background_image'] = 'required';
            if ($request->hasFile('background_image')) {
                $extBackground = $request->file('background_image')->getClientOriginalExtension();
                $rules['background_image'] = [
                    function ($attribute, $value, $fail) use ($extBackground, $allowedBgExts) {
                        if (!in_array($extBackground, $allowedBgExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $rules['title'] = 'nullable';
            $rules['text'] = 'nullable';
        }

        if ($type == 2 || $type == 3) {
            $rules['background_color'] = 'required';
            $rules['background_opacity'] = 'required|numeric|max:1|min:0';
        }

        if ($type == 7) {
            $rules['background_color'] = 'required';
        }

        if ($type == 6 || $type == 7) {
            $rules['end_date'] = 'required';
            $rules['end_time'] = 'required';
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $rules['button_text'] = 'nullable';
            $rules['button_color'] = 'nullable';
        }

        if ($type == 2 || $type == 4 || $type == 6 || $type == 7) {
            $rules['button_url'] = 'nullable';
        }



        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $popup = new Popup;
        $popup->name = $request->name;
        $popup->language_id = $request->language_id;
        $popup->serial_number = $request->serial_number;
        $popup->delay = $request->delay;
        $popup->type = $type;

        if ($type == 1 || $type == 4 || $type == 5 || $type == 7) {
            if ($request->hasFile('image')) {
                $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $directory = public_path('assets/img/popups/');
                @mkdir($directory, 0775, true);
                $request->file('image')->move($directory, $filename);
                $popup->image = $filename;
            }
        }

        if ($type == 2 || $type == 3 || $type == 6) {
            if ($request->hasFile('background_image')) {
                $filename = time() . '.' . $request->file('background_image')->getClientOriginalExtension();
                $directory = public_path('assets/img/popups/');
                @mkdir($directory, 0775, true);
                $request->file('background_image')->move($directory, $filename);
                $popup->background_image = $filename;
            }
        }

        if ($type == 2 || $type == 3) {
            $popup->background_color = $request->background_color;
            $popup->background_opacity = $request->background_opacity;
        }

        if ($type == 7) {
            $popup->background_color = $request->background_color;
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $popup->button_text = $request->button_text;
            $popup->button_color = $request->button_color;
        }

        if ($type == 2 || $type == 4 || $type == 6 || $type == 7) {
            $popup->button_url = $request->button_url;
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $popup->title = $request->title;
            $popup->text = $request->text;
        }

        if ($type == 6 || $type == 7) {
            $popup->end_date = $request->end_date;
            $popup->end_time = $request->end_time;
        }

        $popup->save();

        Session::flash('success', 'Popup added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $type = $request->type;

        $rules = [
            'name' => 'required',
            'serial_number' => 'required|integer',
            'delay' => 'required|integer',
        ];

        if ($type == 1 || $type == 4 || $type == 5 || $type == 7) {
            if ($request->hasFile('image')) {
                $image = $request->image;
                $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
                $extImage = $request->file('image')->getClientOriginalExtension();

                $rules['image'] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }
        }

        if ($type == 2 || $type == 3 || $type == 6) {
            if ($request->hasFile('background_image')) {
                $background = $request->background_image;
                $allowedBgExts = array('jpg', 'png', 'jpeg', 'svg');
                $extBackground = $request->file('background_image')->getClientOriginalExtension();

                $rules['background_image'] = [
                    function ($attribute, $value, $fail) use ($extBackground, $allowedBgExts) {
                        if (!in_array($extBackground, $allowedBgExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $rules['title'] = 'nullable';
            $rules['text'] = 'nullable';
        }

        if ($type == 2 || $type == 3) {
            $rules['background_color'] = 'required';
            $rules['background_opacity'] = 'required|numeric|max:1|min:0';
        }

        if ($type == 7) {
            $rules['background_color'] = 'required';
        }

        if ($type == 6 || $type == 7) {
            $rules['end_date'] = 'required';
            $rules['end_time'] = 'required';
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $rules['button_text'] = 'nullable';
            $rules['button_color'] = 'nullable';
        }

        if ($type == 2 || $type == 4 || $type == 6 || $type == 7) {
            $rules['button_url'] = 'nullable';
        }



        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $popup = Popup::where('id', $request->popup_id)->first();
        $popup->name = $request->name;
        $popup->serial_number = $request->serial_number;
        $popup->delay = $request->delay;

        if ($type == 1 || $type == 4 || $type == 5 || $type == 7) {

            if ($request->hasFile('image')) {
                $filename = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $directory = public_path('assets/img/popups/');
                @mkdir($directory, 0775, true);
                $request->file('image')->move($directory, $filename);
                @unlink(public_path('assets/img/popups/') . $popup->image);
                $popup->image = $filename;
            }
        }

        if ($type == 2 || $type == 3 || $type == 6) {
            if ($request->hasFile('background_image')) {
                $filename = time() . '.' . $request->file('background_image')->getClientOriginalExtension();
                $directory = public_path('assets/img/popups/');
                @mkdir($directory, 0775, true);
                $request->file('background_image')->move($directory, $filename);
                @unlink(public_path('assets/img/popups/') . $popup->background_image);
                $popup->background_image = $filename;
            }
        }

        if ($type == 2 || $type == 3) {
            $popup->background_color = $request->background_color;
            $popup->background_opacity = $request->background_opacity;
        }

        if ($type == 7) {
            $popup->background_color = $request->background_color;
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $popup->button_text = $request->button_text;
            $popup->button_color = $request->button_color;
        }

        if ($type == 2 || $type == 4 || $type == 6 || $type == 7) {
            $popup->button_url = $request->button_url;
        }

        if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7) {
            $popup->title = $request->title;
            $popup->text = $request->text;
        }

        if ($type == 6 || $type == 7) {
            $popup->end_date = $request->end_date;
            $popup->end_time = $request->end_time;
        }

        $popup->save();

        Session::flash('success', 'Popup updated successfully!');
        return "success";
    }


    public function delete(Request $request)
    {

        $popup = Popup::where('id', $request->popup_id)->first();
        @unlink(public_path('assets/img/popups/') . $popup->image);
        @unlink(public_path('assets/img/popups/') . $popup->background_image);
        $popup->delete();

        Session::flash('success', 'Popup deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $popup = Popup::where('id', $id)->first();
            @unlink(public_path('assets/img/popups/') . $popup->image);
            @unlink(public_path('assets/img/popups/') . $popup->background_image);
            $popup->delete();
        }

        Session::flash('success', 'Popups deleted successfully!');
        return "success";
    }

    public function status(Request $request)
    {

        $po = Popup::where('id', $request->popup_id)->first();
        $po->status = $request->status;
        $po->save();

        Session::flash('success', 'Status changed!');
        return back();
    }
}
