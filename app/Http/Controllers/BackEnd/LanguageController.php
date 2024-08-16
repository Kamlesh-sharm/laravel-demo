<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageStoreRequest;
use App\Http\Requests\LanguageUpdateRequest;
use App\Models\BasicSettings\CookieAlert;
use App\Models\BasicSettings\PageHeading;
use App\Models\BasicSettings\SEO;
use App\Models\BlogManagement\Blog;
use App\Models\BlogManagement\BlogCategory;
use App\Models\BlogManagement\BlogContent;
use App\Models\FAQ;
use App\Models\Footer\FooterQuickLink;
use App\Models\Footer\FooterText;
use App\Models\GalleryManagement\Gallery;
use App\Models\GalleryManagement\GalleryCategory;
use App\Models\HomePage\Brand;
use App\Models\HomePage\Facility;
use App\Models\HomePage\HeroSlider;
use App\Models\HomePage\HeroStatic;
use App\Models\HomePage\IntroCountInfo;
use App\Models\HomePage\IntroSection;
use App\Models\HomePage\SectionHeading;
use App\Models\HomePage\Testimonial;
use App\Models\Language;
use App\Models\Menu;
use App\Models\PackageManagement\Package;
use App\Models\PackageManagement\PackageBooking;
use App\Models\PackageManagement\PackageCategory;
use App\Models\PackageManagement\PackageContent;
use App\Models\PackageManagement\PackageImage;
use App\Models\PackageManagement\PackageLocation;
use App\Models\PackageManagement\PackagePlan;
use App\Models\PackageManagement\PackageReview;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\Popup;
use App\Models\RoomManagement\Room;
use App\Models\RoomManagement\RoomAmenity;
use App\Models\RoomManagement\RoomBooking;
use App\Models\RoomManagement\RoomCategory;
use App\Models\RoomManagement\RoomContent;
use App\Models\RoomManagement\RoomImage;
use App\Models\RoomManagement\RoomReview;
use App\Models\ServiceManagement\Service;
use App\Models\ServiceManagement\ServiceContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\json_decode;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::all();

        return view('backend.language.index', compact('languages'));
    }

    public function store(LanguageStoreRequest $request)
    {
        // get all the keywords from the default file of language
        $data = file_get_contents(resource_path('lang/') . 'default.json');

        // make a new json file for the new language
        $file = strtolower($request->code) . '.json';

        // create the path where the new language json file will be stored
        $fileLocated = resource_path('lang/') . $file;

        // finally, put the keywords in the new json file and store the file in lang folder
        file_put_contents($fileLocated, $data);

        // then, store data in db
        $language = Language::create($request->all());
        $data = [];

        $data[] = ['text' => 'Home', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "home"];

        $data[] = ['text' => 'Rooms', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "rooms"];

        $data[] = ['text' => 'Services', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "services"];

        $data[] = ['text' => 'Packages', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "packages"];

        $data[] = ['text' => 'Vendors', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "vendors"];

        $data[] = ['text' => 'Blogs', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "blogs"];

        $data[] = ['text' => 'Gallery', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "gallery"];

        $data[] = ['text' => 'Faq', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "faq"];

        $data[] = ['text' => 'Contact', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "contact"];

        $data[] = ['text' => 'About', "href" => "", "icon" => "empty", "target" => "_self", "title" => "", "type" => "about"];

        Menu::create([
            'language_id' => $language->id,
            'menus' => json_encode($data, true),
        ]);

        session()->flash('success', 'Language added successfully!');

        return 'success';
    }

    public function makeDefault($id)
    {
        // first, make other languages to non-default language
        Language::where('is_default', 1)->update(['is_default' => 0]);

        // second, make the selected language to default language
        $language = Language::where('id', $id)->first();

        $language->update(['is_default' => 1]);

        return back()->with('success', $language->name . ' ' . 'is set as default language.');
    }

    public function update(LanguageUpdateRequest $request)
    {
        $language = Language::where('id', $request->id)->first();

        if ($language->code !== $request->code) {
            /**
             * get all the keywords from the previous file,
             * which was named using previous language code
             */
            $data = file_get_contents(resource_path('lang/') . $language->code . '.json');

            // make a new json file for the new language (code)
            $file = strtolower($request->code) . '.json';

            // create the path where the new language (code) json file will be stored
            $fileLocated = resource_path('lang/') . $file;

            // then, put the keywords in the new json file and store the file in lang folder
            file_put_contents($fileLocated, $data);

            // now, delete the previous language code file
            if (file_exists(resource_path('lang/') . $language->code . '.json')) {
                if (is_file(resource_path('lang/') . $language->code . '.json')) {
                    @unlink(resource_path('lang/') . $language->code . '.json');
                }
            }

            // finally, update the info in db
            $language->update($request->all());
        } else {
            $language->update($request->all());
        }

        session()->flash('success', 'Language updated successfully!');

        return 'success';
    }

    public function addKeyword(Request $request)
    {
        $rules = [
            'keyword' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        $languages = Language::get();
        foreach ($languages as $language) {
            // get all the keywords of the selected language
            $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

            // convert json encoded string into a php associative array
            $keywords = json_decode($jsonData, true);
            $datas = [];
            $datas[$request->keyword] = $request->keyword;

            foreach ($keywords as $key => $keyword) {
                $datas[$key] = $keyword;
            }
            //put data
            $jsonData = json_encode($datas);

            $fileLocated = resource_path('lang/') . $language->code . '.json';

            // put all the keywords in the selected language file
            file_put_contents($fileLocated, $jsonData);
        }

        //for default json
        // get all the keywords of the selected language
        $jsonData = file_get_contents(resource_path('lang/') . 'default.json');

        // convert json encoded string into a php associative array
        $keywords = json_decode($jsonData, true);
        $datas = [];
        $datas[$request->keyword] = $request->keyword;

        foreach ($keywords as $key => $keyword) {
            $datas[$key] = $keyword;
        }
        //put data
        $jsonData = json_encode($datas);

        $fileLocated = resource_path('lang/') . 'default.json';

        // put all the keywords in the selected language file
        file_put_contents($fileLocated, $jsonData);

        Session::flash('success', 'A new keyword add successfully');

        return 'success';
    }


    public function editKeyword($id)
    {
        $language = Language::where('id', $id)->firstOrFail();

        // get all the keywords of the selected language
        $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

        // convert json encoded string into a php associative array
        $keywords = json_decode($jsonData, true);

        return view('backend.language.edit_keyword', compact('language', 'keywords'));
    }

    public function updateKeyword(Request $request, $id)
    {
        $arrData = $request['keyValues'];

        // first, check each key has value or not
        foreach ($arrData as $key => $value) {
            if ($value == null) {
                session()->flash('warning', 'Value is required for "' . $key . '" key.');

                return redirect()->back();
            }
        }

        // convert the array into a string containing the json representation
        $jsonData = json_encode($arrData);

        $language = Language::where('id', $id)->first();

        $fileLocated = resource_path('lang/') . $language->code . '.json';

        // put all the keywords in the selected language file
        file_put_contents($fileLocated, $jsonData);

        session()->flash('success', $language->name . ' language\'s keywords updated successfully!');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $language = Language::where('id', $id)->first();

        if ($language->is_default == 1) {
            return back()->with('warning', 'Default language cannot be delete.');
        } else {
            DB::transaction(function () use ($language) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                $blogContents = BlogContent::where('language_id', $language->id);
                if ($blogContents->count() > 0) {
                    foreach ($blogContents->get() as $bc) {
                        // if this blog has no blog_contents of other languages except the selected one,
                        // then delete the blog...

                        $otherBcs = BlogContent::where('language_id', '<>', $language->id)->where('blog_id', $bc->blog_id)->count();
                        if ($otherBcs == 0) {
                            $blog = Blog::where('id', $bc->blog_id)->first();
                            @unlink(public_path('assets/img/blogs/') . $blog->blog_img);
                            $blog->delete();
                        }


                        $bc->delete();
                    }
                }

                $bcats = BlogCategory::where('language_id', $language->id);
                if ($bcats->count() > 0) {
                    $bcats->delete();
                }

                $brands = Brand::where('language_id', $language->id);
                if ($brands->count() > 0) {
                    foreach ($brands as $brand) {
                        @unlink(public_path('assets/img/brands/') . $brand->brand_img);
                        $brand->delete();
                    }
                }

                $cookies = CookieAlert::where('language_id', $language->id);
                if ($cookies->count() > 0) {
                    $cookies->delete();
                }

                $facilities = Facility::where('language_id', $language->id);
                if ($facilities->count() > 0) {
                    $facilities->delete();
                }

                $faqs = FAQ::where('language_id', $language->id);
                if ($faqs->count() > 0) {
                    $faqs->delete();
                }

                $fqls = FooterQuickLink::where('language_id', $language->id);
                if ($fqls->count() > 0) {
                    $fqls->delete();
                }

                $fts = FooterText::where('language_id', $language->id);
                if ($fts->count() > 0) {
                    $fts->delete();
                }

                $gcats = GalleryCategory::where('language_id', $language->id);
                if ($gcats->count() > 0) {
                    $gcats->delete();
                }

                $gals = Gallery::where('language_id', $language->id);
                if ($gals->count() > 0) {
                    foreach ($gals->get() as $gal) {
                        @unlink(public_path('assets/img/gallery/') . $gal->gallery_img);
                        $gal->delete();
                    }
                }

                $sliders = HeroSlider::where('language_id', $language->id);
                if ($sliders->count() > 0) {
                    foreach ($sliders->get() as $slider) {
                        @unlink(public_path('assets/img/hero_slider/') . $slider->img);
                        $slider->delete();
                    }
                }

                $statics = HeroStatic::where('language_id', $language->id);
                if ($statics->count() > 0) {
                    foreach ($statics->get() as $static) {
                        @unlink(public_path('assets/img/hero_static/') . $static->img);
                        $static->delete();
                    }
                }

                $counters = IntroCountInfo::where('language_id', $language->id);
                if ($counters->count() > 0) {
                    $counters->delete();
                }

                $introSecs = IntroSection::where('language_id', $language->id);
                if ($introSecs->count() > 0) {
                    foreach ($introSecs->get() as $sec) {
                        @unlink(public_path('assets/img/intro_section/') . $sec->intro_img);
                        $sec->delete();
                    }
                }

                $menus = Menu::where('language_id', $language->id);
                if ($menus->count() > 0) {
                    $menus->delete();
                }

                $packageContents = PackageContent::where('language_id', $language->id);
                if ($packageContents->count() > 0) {
                    foreach ($packageContents->get() as $pc) {

                        // if this package has no package_contents of other languages except the selected one,
                        // then delete the package...
                        $otherPcs = PackageContent::where('language_id', '<>', $language->id)->where('package_id', $pc->package_id)->count();
                        if ($otherPcs == 0) {
                            $package = Package::where('id', $pc->package_id)->first();
                            @unlink(public_path('assets/img/package/') . $package->featured_img);

                            $sliders = PackageImage::where('package_id', $package->id)->get();
                            foreach ($sliders as $key => $slider) {
                                @unlink(public_path('assets/img/package-gallery/') . $slider->image);
                            }

                            // delete package bookings
                            $pbookings = PackageBooking::where('package_id', $package->id);
                            if ($pbookings->count() > 0) {
                                foreach ($pbookings->get() as $key => $pb) {
                                    @unlink(public_path('assets/invoices/packages/') . $pb->invoice);
                                    @unlink(public_path('assets/img/attachments/packages/') . $pb->attachment);
                                    $pb->delete();
                                }
                            }

                            // delete package ratings
                            PackageReview::where('package_id', $package->id)->delete();

                            $package->delete();
                        }


                        $pc->delete();
                    }
                }


                PackagePlan::where('language_id', $language->id)->delete();
                PackageLocation::where('language_id', $language->id)->delete();
                PackageCategory::where('language_id', $language->id)->delete();

                $pcats = PackageCategory::where('language_id', $language->id);
                if ($pcats->count() > 0) {
                    $pcats->delete();
                }

                $pageContents = PageContent::where('language_id', $language->id);
                if ($pageContents->count() > 0) {
                    foreach ($pageContents->get() as $pc) {
                        // if this page has no page_contents of other languages except the selected one,
                        // then delete the page...

                        $otherPcs = PageContent::where('language_id', '<>', $language->id)->where('page_id', $pc->page_id)->count();
                        if ($otherPcs == 0) {
                            $page = Page::where('id', $pc->page_id)->first();
                            $page->delete();
                        }


                        $pc->delete();
                    }
                }

                PageHeading::where('language_id', $language->id)->delete();
                Popup::where('language_id', $language->id)->delete();

                $roomContents = RoomContent::where('language_id', $language->id);
                if ($roomContents->count() > 0) {
                    foreach ($roomContents->get() as $rc) {

                        // if this room has no room_contents of other languages except the selected one,
                        // then delete the room...
                        $otherRcs = RoomContent::where('language_id', '<>', $language->id)->where('room_id', $rc->room_id)->count();
                        if ($otherRcs == 0) {
                            $room = Room::where('id', $rc->room_id)->first();
                            @unlink(public_path('assets/img/rooms/') . $room->featured_img);
                            $sliders = RoomImage::where('room_id', $room->id)->get();
                            foreach ($sliders as $key => $slider) {
                                @unlink(public_path('assets/img/room-gallery/') . $slider);
                            }

                            // delete room bookings
                            $rbookings = RoomBooking::where('room_id', $room->id);
                            if ($rbookings->count() > 0) {
                                foreach ($rbookings->get() as $key => $rb) {
                                    @unlink(public_path('assets/invoices/rooms/') . $rb->invoice);
                                    @unlink(public_path('assets/img/attachments/rooms/') . $rb->attachment);
                                    $rb->delete();
                                }
                            }

                            // delete room ratings
                            RoomReview::where('room_id', $room->id)->delete();

                            $room->delete();
                        }


                        $rc->delete();
                    }
                }

                $rcats = RoomCategory::where('language_id', $language->id);
                if ($rcats->count() > 0) {
                    $rcats->delete();
                }

                $amms = RoomAmenity::where('language_id', $language->id);
                if ($amms->count() > 0) {
                    $amms->delete();
                }

                SectionHeading::where('language_id', $language->id)->delete();
                SEO::where('language_id', $language->id)->delete();

                $serviceContents = ServiceContent::where('language_id', $language->id);
                if ($serviceContents->count() > 0) {
                    foreach ($serviceContents->get() as $sc) {
                        // if this service has no service_contents of other languages except the selected one,
                        // then delete the service...

                        $otherScs = ServiceContent::where('language_id', '<>', $language->id)->where('service_id', $sc->service_id)->count();
                        if ($otherScs == 0) {
                            $service = Service::where('id', $sc->service_id)->first();
                            $service->delete();
                        }


                        $sc->delete();
                    }
                }

                Testimonial::where('language_id', $language->id)->delete();
            });





            // first, delete the language json file
            if (file_exists(resource_path('lang/') . $language->code . '.json')) {
                if (is_file(resource_path('lang/') . $language->code . '.json')) {
                    @unlink(resource_path('lang/') . $language->code . '.json');
                }
            }

            // then, delete the info from db
            $language->delete();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return back()->with('success', 'Language deleted successfully!');
        }
    }

    public function rtlcheck($langid)
    {
        if ($langid > 0) {
            $lang = Language::where('id', $langid)->first();
        } else {
            return 0;
        }

        return $lang->direction;
    }
}
