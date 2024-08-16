<?php

namespace App\Providers;

use App\Models\BasicSettings\CookieAlert;
use App\Models\BasicSettings\SEO;
use App\Models\BasicSettings\SocialLink;
use App\Models\BlogManagement\BlogContent;
use App\Models\Footer\FooterQuickLink;
use App\Models\Footer\FooterText;
use App\Models\HomePage\HeroStatic;
use App\Models\HomePage\HomeSection;
use App\Models\Language;
use App\Models\Menu;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    $contextOptions = array(
      "ssl" => array(
        'allow_self_signed' => true,
        "verify_peer"      => false,
        "verify_peer_name" => false,
      ),
    );
    stream_context_set_default($contextOptions);

    Paginator::useBootstrap();

    if (!app()->runningInConsole()) {
      $data = DB::table('basic_settings')
        ->select('favicon', 'website_title', 'logo', 'support_email', 'support_contact', 'address', 'footer_logo', 'primary_color', 'secondary_color', 'theme_version', 'home_version', 'disqus_shortname', 'is_disqus', 'base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'package_category_status', 'room_category_status', 'latitude', 'longitude', 'google_recaptcha_status', 'google_recaptcha_site_key', 'google_recaptcha_secret_key', 'maintenance_img', 'maintenance_status', 'maintenance_msg', 'is_tawkto', 'tawkto_property_id', 'is_whatsapp', 'whatsapp_popup', 'whatsapp_number', 'whatsapp_header_title', 'whatsapp_popup_message', 'breadcrumb_overlay_color', 'breadcrumb_overlay_opacity', 'preloader', 'preloader_status', 'hero_video_link')
        ->first();


      // send this information to only back-end view files
      View::composer('backend.*', function ($view) {
        $websiteSettings = DB::table('basic_settings')->select('package_category_status', 'theme_version')
          ->where('uniqid', 12345)
          ->first();

        $language = Language::where('is_default', 1)->first();
        $footerText = FooterText::where('language_id', $language->id)->first();
        if (Auth::guard('admin')->check()) {
          $admin = Auth::guard('admin')->user();
          if (!empty($admin->role)) {
            $permissions = $admin->role->permissions;
            $permissions = json_decode($permissions, true);
          } else {
            $permissions = [];
          }
          $view->with('permissions', $permissions);
          $view->with('admin', $admin);
        }

        $view->with('settings', $websiteSettings);
        $view->with('defaultLang', $language);
        $view->with('footerTextInfo', $footerText);
      });

      // send this information to only vendors view files
      View::composer('vendors.*', function ($view) {


        $language = Language::where('is_default', 1)->first();

        $seo = SEO::where('language_id', $language->id)->first();

        $footerText = FooterText::where('language_id', $language->id)->first();

        $websiteSettings = DB::table('basic_settings')->select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')->first();

        $view->with('defaultLang', $language);
        $view->with('settings', $websiteSettings);
        $view->with('seo', $seo);
        $view->with('footerTextInfo', $footerText);
      });


      // send this information to only front-end view files
      View::composer(['frontend.*'], function ($view) {
        // get the current locale of this website
        if (Session::has('currentLocaleCode')) {
          $locale = Session::get('currentLocaleCode');
        }
        if (empty($locale)) {
          $language = Language::where('is_default', 1)->first();
        } else {
          $language = Language::where('code', $locale)->first();
        }
        if (empty($language)) {
          $language = Language::where('is_default', 1)->first();
        }

        $cookie = CookieAlert::where('language_id', $language->id)->first();

        if (Menu::where('language_id', $language->id)->count() > 0) {
          $menus = Menu::where('language_id', $language->id)->first()->menus;
        } else {
          $menus = json_encode([]);
        }
        $allSocialLinks = SocialLink::orderBy('serial_number', 'asc')->get();

        // get all the languages of this website
        $allLanguages = Language::all();


        $seo = SEO::where('language_id', $language->id)->first();
        $footerData = FooterText::where('language_id', $language->id)->first();
        $sections = HomeSection::firstOrFail();

        $footerQuickLinks = FooterQuickLink::where('language_id', $language->id)
          ->orderBy('serial_number', 'asc')
          ->get();

        $footerRecentBlogs = BlogContent::with('blog')
          ->where('language_id', $language->id)
          ->orderBy('blog_id', 'desc')
          ->limit(3)
          ->get();

        $popups = $language->popups()->where('status', 1)->orderBy('serial_number', 'ASC')->get();

        $hero = HeroStatic::where('language_id', $language->id);
        if ($hero->count() > 0) {
          $hero = $hero->firstOrFail();
        } else {
          $hero = NULL;
        }


        $view->with('menus', $menus);
        $view->with('seo', $seo);
        $view->with('socialLinkInfos', $allSocialLinks);
        $view->with('allLanguageInfos', $allLanguages);
        $view->with('currentLanguageInfo', $language);
        $view->with('footerInfo', $footerData);
        $view->with('quickLinkInfos', $footerQuickLinks);
        $view->with('footerBlogInfos', $footerRecentBlogs);
        $view->with('sections', $sections);
        $view->with('popups', $popups);
        $view->with('hero', $hero);
        $view->with('cookie', $cookie);
      });


      // send this information to both front-end and back-end view files
      View::share(['websiteInfo' => $data]);
    }
  }
}
