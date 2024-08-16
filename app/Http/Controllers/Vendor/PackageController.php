<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\PackageManagement\Package;
use App\Models\PackageManagement\PackageContent;
use App\Models\PackageManagement\PackageImage;
use App\Models\PackageManagement\PackageLocation;
use App\Models\PackageManagement\PackagePlan;
use App\Traits\MiscellaneousTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class PackageController extends Controller
{
    use MiscellaneousTrait;

    public function packages(Request $request)
    {
        $language = Language::where('is_default', 1)->firstOrFail();
        $information['language'] = $language;

        $languageId = $language->id;
        $title = null;

        if ($request->filled('title')) {
            $title = $request['title'];
        }

        $packageIds = [];
        if ($request->filled('title')) {
            $package_contents = PackageContent::where('title', 'like', '%' . $title . '%')->get();
            foreach ($package_contents as $package_content) {
                if (!in_array($package_content->package_id, $packageIds)) {
                    array_push($packageIds, $package_content->package_id);
                }
            }
        }

        $information['packages'] = Package::where('vendor_id', Auth::guard('vendor')->user()->id)
            ->with([
                'package_content' => function ($q) use ($languageId) {
                    $q->where('language_id', $languageId);
                }
            ])
            ->when($title, function ($query) use ($packageIds) {
                return $query->whereIn('id', $packageIds);
            })
            ->orderBy('id', 'desc')
            ->get();

        $information['currencyInfo'] = MiscellaneousTrait::getCurrencyInfo();

        return view('vendors.packages.packages', $information);
    }

    public function createPackage()
    {
        // get all the languages from db
        $information['languages'] = Language::all();

        $information['basicSettings'] = DB::table('basic_settings')
            ->select('package_category_status')
            ->where('uniqid', 12345)
            ->first();

        return view('vendors.packages.create_package', $information);
    }


    public function gallerystore(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');
        $rules = [
            'file' => [
                'dimensions:width=750,height=400',
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    $ext = $img->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                }
            ]
        ];
        $messages = [
            'file.dimensions' => 'The file has invalid image dimensions ' . $img->getClientOriginalName()
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $filename = uniqid() . '.jpg';
        @mkdir(public_path('assets/img/package-gallery/'), 0775, true);
        $img->move(public_path('assets/img/package-gallery/'), $filename);
        $pi = new PackageImage();
        if (!empty($request->package_id)) {
            $pi->package_id = $request->package_id;
        }
        $pi->image = $filename;
        $pi->save();
        return response()->json(['status' => 'success', 'file_id' => $pi->id]);
    }

    public function images($portid)
    {
        $images = PackageImage::where('package_id', $portid)->get();
        return $images;
    }

    public function imagedbrmv(Request $request)
    {
        $pi = PackageImage::where('id', $request->fileid)->first();
        $package_id = $pi->package_id;
        $image_count = PackageImage::where('package_id', $package_id)->get()->count();
        if ($image_count > 1) {
            @unlink(public_path('assets/img/package-gallery/') . $pi->image);
            $pi->delete();
            return $pi->id;
        } else {
            return 'false';
        }
    }

    public function storePackage(Request $request)
    {
        $rules = [
            'slider_images' => 'required',
            'number_of_days' => 'required|numeric|min:1',
            'plan_type' => 'required',
            'pricing_type' => 'required',
            'fixed_package_price' => 'required_if:pricing_type,==,fixed',
            'per_person_package_price' => 'required_if:pricing_type,==,per-person'
        ];

        $featuredImgURL = $request->featured_img;

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');

        $rules['featured_img'] = 'required';
        if ($request->hasFile('featured_img')) {
            $featuredImgExt = $request->file('featured_img')->getClientOriginalExtension();
            $rules['featured_img'] = [
                function ($attribute, $value, $fail) use ($allowedExtensions, $featuredImgExt) {
                    if (!in_array($featuredImgExt, $allowedExtensions)) {
                        $fail('Only .jpg, .jpeg, .png and .svg file is allowed for featured image.');
                    }
                }
            ];
            $rules['featured_img'] = 'dimensions:width=300,height=360';
        }


        $messages = [
            'featured_img.required' => 'The package\'s featured image is required.',
        ];

        $languages = Language::all();

        $settings = DB::table('basic_settings')->select('package_category_status')
            ->where('uniqid', 12345)
            ->first();

        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required|max:255';

            if ($settings->package_category_status == 1) {
                $rules[$language->code . '_category'] = 'required';
            }

            $rules[$language->code . '_description'] = 'required|min:15';

            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';

            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

            if ($settings->package_category_status == 1) {
                $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language';
            }

            $messages[$language->code . '_description.required'] = 'The description field is required for ' . $language->name . ' language';

            $messages[$language->code . '_description.min'] = 'The description field atleast have 15 characters for ' . $language->name . ' language';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $package = new Package();

        if ($request->hasFile('featured_img')) {
            $filename = time() . '.' . $featuredImgURL->getClientOriginalExtension();
            $directory = public_path('assets/img/package/');
            @mkdir($directory, 0775, true);
            $request->file('featured_img')->move($directory, $filename);
            $package->featured_img = $filename;
        }
        $package->plan_type = $request->plan_type;
        $package->number_of_days = $request->number_of_days;
        $package->max_persons = $request->max_persons;
        $package->pricing_type = $request->pricing_type;

        if ($request->pricing_type == 'fixed') {
            $package->package_price = $request->fixed_package_price;
        } elseif ($request->pricing_type == 'per-person') {
            $package->package_price = $request->per_person_package_price;
        }

        $package->vendor_id = Auth::guard('vendor')->user()->id;
        $package->email = $request->email;
        $package->phone = $request->phone;
        $package->save();

        $slders = $request->slider_images;

        foreach ($slders as $key => $id) {
            $package_image = PackageImage::where('id', $id)->first();
            if ($package_image) {
                $package_image->package_id = $package->id;
                $package_image->save();
            }
        }

        foreach ($languages as $language) {
            $packageContent = new PackageContent();
            $packageContent->language_id = $language->id;
            $packageContent->package_category_id = $request[$language->code . '_category'];
            $packageContent->package_id = $package->id;
            $packageContent->title = $request[$language->code . '_title'];
            $packageContent->slug = createSlug($request[$language->code . '_title']);
            $packageContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
            $packageContent->meta_keywords = $request[$language->code . '_meta_keywords'];
            $packageContent->meta_description = $request[$language->code . '_meta_description'];
            $packageContent->save();
        }

        session()->flash('success', 'New tour package added successfully!');

        return 'success';
    }

    public function updateFeaturedPackage(Request $request)
    {
        $package = Package::where('id', $request->packageId)->first();

        if ($request->is_featured == 1) {
            $package->update(['is_featured' => 1]);

            session()->flash('success', 'Package featured successfully!');
        } else {
            $package->update(['is_featured' => 0]);

            session()->flash('success', 'Package Unfeatured successfully!');
        }

        return redirect()->back();
    }

    public function editPackage($id)
    {
        $vendor_id = Auth::guard('vendor')->user()->id;
        $information['package'] = Package::where([['id', $id], ['vendor_id', $vendor_id]])->firstOrFail();

        // get all the languages from db
        $information['languages'] = Language::all();

        $information['basicSettings'] = DB::table('basic_settings')
            ->select('package_category_status')
            ->where('uniqid', 12345)
            ->first();

        return view('vendors.packages.edit_package', $information);
    }

    public function getSliderImages($id)
    {
        $package = Package::where('id', $id)->first();
        $sliderImages = json_decode($package->slider_imgs);

        $images = [];

        // concatanate slider image with image location
        foreach ($sliderImages as $key => $sliderImage) {
            $data = url('assets/img/packages/slider_images/' . $sliderImage);
            array_push($images, $data);
        }

        return Response::json($images, 200);
    }

    public function updatePackage(Request $request, $id)
    {
        $rules = [
            'number_of_days' => 'required|numeric|min:1',
            'plan_type' => 'required',
            'pricing_type' => 'required',
            'fixed_package_price' => 'required_if:pricing_type,==,fixed',
            'per_person_package_price' => 'required_if:pricing_type,==,per-person'
        ];

        $featuredImgURL = $request->featured_img;

        $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');

        if ($request->hasFile('featured_img')) {
            $featuredImgExt = $request->file('featured_img')->getClientOriginalExtension();
            $rules['featured_img'] = function ($attribute, $value, $fail) use ($allowedExtensions, $featuredImgExt) {
                if (!in_array($featuredImgExt, $allowedExtensions)) {
                    $fail('Only .jpg, .jpeg, .png and .svg file is allowed for featured image.');
                }
            };
            $rules['featured_img'] = 'dimensions:width=300,height=360';
        }

        $languages = Language::all();

        $settings = DB::table('basic_settings')->select('package_category_status')
            ->where('uniqid', 12345)
            ->first();

        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required|max:255';

            if ($settings->package_category_status == 1) {
                $rules[$language->code . '_category'] = 'required';
            }

            $rules[$language->code . '_description'] = 'required|min:15';

            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';

            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

            if ($settings->package_category_status == 1) {
                $messages[$language->code . '_category.required'] = 'The category field is required for ' . $language->name . ' language';
            }

            $messages[$language->code . '_description.required'] = 'The description field is required for ' . $language->name . ' language';

            $messages[$language->code . '_description.min'] = 'The description field atleast have 15 characters for ' . $language->name . ' language';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $package = Package::where('id', $id)->first();

        if ($request->hasFile('featured_img')) {
            $filename = time() . '.' . $featuredImgURL->getClientOriginalExtension();
            $directory = public_path('assets/img/package/');
            @mkdir($directory, 0775, true);
            @unlink($directory . $package->featured_img);
            $request->file('featured_img')->move($directory, $filename);
            $featured_image = $filename;
        }

        // get the package price that admin has selected
        if ($request->pricing_type == 'negotiable') {
            $amount = null;
        } elseif ($request->pricing_type == 'fixed') {
            $amount = $request->fixed_package_price;
        } elseif ($request->pricing_type == 'per-person') {
            $amount = $request->per_person_package_price;
        }

        $package->update([
            'number_of_days' => $request->number_of_days,
            'featured_img' => $request->hasFile('featured_img') ? $featured_image : $package->featured_img,
            'plan_type' => $request->plan_type,
            'max_persons' => $request->max_persons,
            'pricing_type' => $request->pricing_type,
            'package_price' => isset($amount) ? $amount : $package->package_price,
            'email' => $request->email,
            'phone' => $request->phone
        ]);

        foreach ($languages as $language) {
            $packageContent = PackageContent::where('package_id', $id)
                ->where('language_id', $language->id)
                ->first();

            $content = [
                'language_id' => $language->id,
                'package_id' => $package->id,
                'package_category_id' => $request[$language->code . '_category'],
                'title' => $request[$language->code . '_title'],
                'slug' => createSlug($request[$language->code . '_title']),
                'description' => Purifier::clean($request[$language->code . '_description'], 'youtube'),
                'meta_keywords' => $request[$language->code . '_meta_keywords'],
                'meta_description' => $request[$language->code . '_meta_description']
            ];

            if (!empty($packageContent)) {
                $packageContent->update($content);
            } else {
                PackageContent::create($content);
            }
        }

        session()->flash('success', 'Tour package updated successfully!');

        return 'success';
    }

    public function deletePackage(Request $request)
    {
        $package = Package::where('id', $request->package_id)->first();

        if ($package->packageLocationList()->count() > 0) {
            session()->flash('warning', 'First delete all the locations of this package!');

            return redirect()->back();
        }

        if ($package->packagePlanList()->count() > 0) {
            session()->flash('warning', 'First delete all the plans of this package!');

            return redirect()->back();
        }

        // first, delete all the contents of this package
        $contents = $package->packageContent()->get();

        foreach ($contents as $content) {
            $content->delete();
        }

        // second, delete all the slider images of this package
        if (!is_null($package->slider_imgs)) {
            $images = json_decode($package->slider_imgs);

            foreach ($images as $image) {
                if (file_exists(public_path('assets/img/packages/slider_images/') . $image)) {
                    @unlink(public_path('assets/img/packages/slider_images/') . $image);
                }
            }
        }

        // third, delete featured image of this package
        if (!is_null($package->featured_img) && file_exists(public_path('assets/img/packages/') . $package->featured_img)) {
            @unlink(public_path('assets/img/packages/') . $package->featured_img);
        }

        // finally, delete this package
        $package->delete();

        session()->flash('success', 'Tour package deleted successfully!');

        return redirect()->back();
    }

    public function bulkDeletePackage(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $package = Package::where('id', $id)->first();

            if ($package->packageLocationList()->count() > 0) {
                session()->flash('warning', 'First delete all the locations of those packages!');

                /**
                 * this 'success' is returning for ajax call.
                 * here, by returning the 'success' ajax will show the flash error message
                 */
                return 'success';
            }

            if ($package->packagePlanList()->count() > 0) {
                session()->flash('warning', 'First delete all the plans of those packages!');

                /**
                 * this 'success' is returning for ajax call.
                 * here, by returning the 'success' ajax will show the flash error message
                 */
                return 'success';
            }

            // first, delete all the contents of this package
            $contents = $package->packageContent()->get();

            foreach ($contents as $content) {
                $content->delete();
            }

            // second, delete all the slider images of this package
            if (!is_null($package->slider_imgs)) {
                $images = json_decode($package->slider_imgs);

                foreach ($images as $image) {
                    if (file_exists(public_path('assets/img/packages/slider_images/') . $image)) {
                        @unlink(public_path('assets/img/packages/slider_images/') . $image);
                    }
                }
            }

            // third, delete featured image of this package
            if (!is_null($package->featured_img) && file_exists(public_path('assets/img/packages/') . $package->featured_img)) {
                @unlink(public_path('assets/img/packages/') . $package->featured_img);
            }

            // finally, delete this package
            $package->delete();
        }

        session()->flash('success', 'Tour packages deleted successfully!');

        /**
         * this 'success' is returning for ajax call.
         * if return == 'success' then ajax will reload the page.
         */
        return 'success';
    }


    public function storeLocation(Request $request)
    {
        $rule = [
            'name' => 'required'
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $lang = Language::where('id', $request->language_id)->first();

        PackageLocation::create($request->except('language_id') + [
            'language_id' => $lang->id
        ]);

        session()->flash('success', 'New location added successfully!');

        return 'success';
    }

    public function viewLocations(Request $request, $package_id)
    {
        $vendor_id = Auth::guard('vendor')->user()->id;
        // first, get the language info from db
        $information['langs'] = Language::all();
        $language = Language::where('code', $request->language)->firstOrFail();
        $information['language'] = $language;

        // then, get the locations of selected package
        $information['locations'] = PackageLocation::where('language_id', $language->id)
            ->where('package_id', $package_id)
            ->orderBy('id', 'desc')
            ->get();
        $package = Package::where([['id', $package_id], ['vendor_id', $vendor_id]])->firstOrFail();

        return view('vendors.packages.locations', $information);
    }

    public function updateLocation(Request $request)
    {
        $rule = [
            'name' => 'required'
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        PackageLocation::where('id', $request->location_id)->first()->update($request->all());

        session()->flash('success', 'Location updated successfully!');

        return 'success';
    }

    public function deleteLocation(Request $request)
    {
        PackageLocation::where('id', $request->location_id)->first()->delete();

        session()->flash('success', 'Location deleted successfully!');

        return redirect()->back();
    }

    public function bulkDeleteLocation(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            PackageLocation::where('id', $id)->first()->delete();
        }

        session()->flash('success', 'Locations deleted successfully!');

        /**
         * this 'success' is returning for ajax call.
         * if return == 'success' then ajax will reload the page.
         */
        return 'success';
    }


    public function storeDaywisePlan(Request $request)
    {
        $rules = [
            'day_number' => 'required',
            'plan' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $lang = Language::where('id', $request->language_id)->first();

        PackagePlan::create($request->except('language_id') + [
            'language_id' => $lang->id
        ]);

        session()->flash('success', 'New plan added successfully!');

        return Response::json('success', 200);
    }

    public function storeTimewisePlan(Request $request)
    {
        $rules = [
            'start_time' => 'required',
            'end_time' => 'required',
            'plan' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $lang = Language::where('id', $request->language_id)->first();

        PackagePlan::create($request->except('language_id') + [
            'language_id' => $lang->id
        ]);

        session()->flash('success', 'New plan added successfully!');

        return Response::json('success', 200);
    }

    public function viewPlans(Request $request, $package_id)
    {
        $vendor_id = Auth::guard('vendor')->user()->id;
        $information['langs'] = Language::all();
        // first, get the language info from db
        $language = Language::where('code', $request->language)->firstOrFail();
        $information['language'] = $language;

        // then, get the plans of selected package
        $information['plans'] = PackagePlan::where('language_id', $language->id)
            ->where('package_id', $package_id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        $package = Package::where([['id', $package_id], ['vendor_id', $vendor_id]])->firstOrFail();
        $information['package'] = $package;

        if ($package->plan_type == 'daywise') {
            return view('vendors.packages.daywise_plans', $information);
        } else if ($package->plan_type == 'timewise') {
            return view('vendors.packages.timewise_plans', $information);
        }
    }

    public function updateDaywisePlan(Request $request)
    {
        $rules = [
            'day_number' => 'required',
            'edit_plan' => 'required'
        ];

        $messages['edit_plan'] = 'The plan feild is required.';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        $in = $request->all();
        $in['plan'] = $request->edit_plan;

        PackagePlan::where('id', $request->plan_id)->first()->update($in);

        session()->flash('success', 'Plan updated successfully!');

        return 'success';
    }

    public function updateTimewisePlan(Request $request)
    {
        $rules = [
            'start_time' => 'required',
            'end_time' => 'required',
            'edit_plan' => 'required'
        ];
        $messages['edit_plan'] = 'The plan feild is required.';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        $in = $request->all();
        $in['plan'] = $request->edit_plan;

        PackagePlan::where('id', $request->plan_id)->first()->update($in);

        session()->flash('success', 'Plan updated successfully!');

        return 'success';
    }

    public function deletePlan(Request $request)
    {
        PackagePlan::where('id', $request->plan_id)->first()->delete();

        session()->flash('success', 'Plan deleted successfully!');

        return redirect()->back();
    }

    public function bulkDeletePlan(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            PackagePlan::where('id', $id)->first()->delete();
        }

        session()->flash('success', 'Plans deleted successfully!');

        /**
         * this 'success' is returning for ajax call.
         * if return == 'success' then ajax will reload the page.
         */
        return 'success';
    }
}
