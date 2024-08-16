<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\HomeSection;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;

class SectionsController extends Controller
{
    public function sections(Request $request)
    {
        $data['sections'] = HomeSection::first();

        return view('backend.home_page.sections', $data);
    }

    public function updatesections(Request $request)
    {
        $settings = DB::table('basic_settings')->select('theme_version')->first();
        $sections = HomeSection::firstOrFail();

        $sections->search_section = $request->search_section;
        $sections->intro_section = $request->intro_section;
        $sections->featured_rooms_section = $request->featured_rooms_section;
        $sections->featured_services_section = $request->featured_services_section;

        if ($settings->theme_version == 'theme_two') {
            $sections->faq_section = $request->faq_section;
            $sections->blogs_section = $request->blogs_section;
        }


        $sections->statistics_section = $request->statistics_section;
        $sections->video_section = $request->video_section;
        $sections->featured_package_section = $request->featured_package_section;

        if ($settings->theme_version == 'theme_one') {
            $sections->facilities_section = $request->facilities_section;
        }

        $sections->testimonials_section = $request->testimonials_section;
        $sections->brand_section = $request->brand_section;
        $sections->top_footer_section = $request->top_footer_section;
        $sections->copyright_section = $request->copyright_section;
        $sections->save();

        Session::flash('success', 'Sections customized successfully!');
        return back();
    }
}
