<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\AppearanceRequest;
use App\Http\Requests\CurrencyRequest;
use App\Http\Requests\MailFromAdminRequest;
use App\Models\Commission;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class BasicSettingsController extends Controller
{
  //general_settings
  public function general_settings()
  {
    $data = [];

    $data['data'] = DB::table('basic_settings')->first();
    $data['commission'] = Commission::first();

    return view('backend.basic_settings.general-settings', $data);
  }
  //update general settings
  public function update_general_setting(Request $request)
  {
    $data = DB::table('basic_settings')->first();
    $rules = [];

    $rules = [
      'website_title' => 'required',
      'support_email' => 'required',
      'support_contact' => 'required',
      'address' => 'required',

      'preloader_status' => 'required',

      'base_currency_symbol' => 'required',
      'base_currency_symbol_position' => 'required',
      'base_currency_text' => 'required',
      'base_currency_text_position' => 'required',
      'base_currency_rate' => 'required|numeric',
      'room_booking_commission' => 'required',
      'package_booking_commission' => 'required',
      'primary_color' => 'required',
      'secondary_color' => 'required',
      'breadcrumb_overlay_color' => 'required',
      'breadcrumb_overlay_opacity' => 'required|numeric|min:0|max:1'
    ];



    if (!$request->filled('logo') && is_null($data->logo)) {
      $rules['logo'] = 'required';
    }
    if ($request->hasFile('logo')) {
      $rules['logo'] = new ImageMimeTypeRule();
    }
    if ($request->hasFile('preloader')) {
      $rules['preloader'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('logo')) {
      $logoName = UploadFile::update(public_path('assets/img/'), $request->file('logo'), $data->logo);
    } else {
      $logoName = $data->logo;
    }
    if ($request->hasFile('favicon')) {
      $iconName = UploadFile::update(public_path('assets/img/'), $request->file('favicon'), $data->favicon);
    } else {
      $iconName = $data->favicon;
    }

    if ($request->hasFile('preloader')) {
      $preloaderName = UploadFile::update(public_path('assets/img/'), $request->file('preloader'), $data->preloader);
    } else {
      $preloaderName = $data->preloader;
    }

    ///update commission
    $commission = Commission::first();

    if (empty($commission)) {
      Commission::query()->create($request->all());
    } else {
      $commission->update($request->all());
    }

    //update or insert data to basic settigs table 
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'website_title' => $request->website_title,
        'logo' => $logoName,
        'favicon' => $iconName,
        'preloader' => $preloaderName,
        'preloader_status' => $request->preloader_status,

        'website_title' => $request->website_title,
        'support_email' => $request->support_email,
        'support_contact' => $request->support_contact,
        'address' => $request->address,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,

        'primary_color' => $request->primary_color,
        'secondary_color' => $request->secondary_color,
        'breadcrumb_overlay_color' => $request->breadcrumb_overlay_color,
        'breadcrumb_overlay_opacity' => $request->breadcrumb_overlay_opacity,

        'base_currency_symbol' => $request->base_currency_symbol,
        'base_currency_symbol_position' => $request->base_currency_symbol_position,
        'base_currency_text' => $request->base_currency_text,
        'base_currency_text_position' => $request->base_currency_text_position,
        'base_currency_rate' => $request->base_currency_rate
      ]
    );
    $request->session()->flash('success', 'Update general settings successfully.!');

    return redirect()->back();
  }

  public function fileManager()
  {
    return view('backend.basic_settings.file-manager');
  }
  public function themeVersion()
  {
    $data = DB::table('basic_settings')->select('theme_version', 'home_version')
      ->where('uniqid', 12345)
      ->first();

    return view('backend.theme_version', ['data' => $data]);
  }

  public function updateThemeVersion(Request $request)
  {
    $rule = [
      'theme_version' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    DB::table('basic_settings')->update([
      'theme_version' => $request->theme_version,
      'home_version' => $request->home_version
    ]);

    session()->flash('success', 'Version updated successfully!');

    return 'success';
  }


  public function favicon()
  {
    $data = DB::table('basic_settings')->select('favicon')->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.favicon', ['data' => $data]);
  }

  public function updateFavicon(Request $request)
  {
    $faviconURL = $request->favicon;

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    $rule['favicon'] = 'required';

    if ($request->hasFile('favicon')) {
      $fileExtension = $request->file('favicon')->getClientOriginalExtension();
      $rule['favicon'] = [
        function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
          if (!in_array($fileExtension, $allowedExtensions)) {
            $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
          }
        }
      ];
    }


    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    // first, get the favicon from db
    $data = DB::table('basic_settings')->select('favicon')->where('uniqid', 12345)
      ->first();

    // second, delete the previous favicon from local storage
    if (!is_null($data->favicon) && file_exists(public_path('assets/img/') . $data->favicon)) {
      @unlink(public_path('assets/img/') . $data->favicon);
    }

    // third, set a name for the favicon and store it to local storage
    $iconName = time() . '.' . $fileExtension;
    $directory = public_path('assets/img/');

    if (!file_exists($directory)) {
      @mkdir($directory, 0775, true);
    }

    copy($faviconURL, $directory . $iconName);

    // finally, store the favicon into db
    DB::table('basic_settings')->update(['favicon' => $iconName]);

    session()->flash('success', 'Favicon updated successfully!');

    return redirect()->back();
  }


  public function logo()
  {
    $data = DB::table('basic_settings')->select('logo')->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.logo', ['data' => $data]);
  }

  public function updateLogo(Request $request)
  {
    $logoURL = $request->logo;

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    $rule = [];
    $rule['logo'] = 'required';
    if ($request->hasFile('logo')) {
      $fileExtension = $request->file('logo')->getClientOriginalExtension();
      $rule['logo'] = [
        function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
          if (!in_array($fileExtension, $allowedExtensions)) {
            $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
          }
        }
      ];
    }

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    // first, get the logo from db
    $data = DB::table('basic_settings')->select('logo')->where('uniqid', 12345)
      ->first();

    // second, delete the previous logo from local storage
    if (!is_null($data->logo) && file_exists(public_path('assets/img/') . $data->logo)) {
      @unlink(public_path('assets/img/') . $data->logo);
    }

    // third, set a name for the logo and store it to local storage
    $logoName = time() . '.' . $fileExtension;
    $directory = public_path('assets/img/');

    if (!file_exists($directory)) {
      @mkdir($directory, 0775, true);
    }

    copy($logoURL, $directory . $logoName);

    // finally, store the logo into db
    DB::table('basic_settings')->update(['logo' => $logoName]);

    session()->flash('success', 'Logo updated successfully!');

    return redirect()->back();
  }


  public function information()
  {
    $data = DB::table('basic_settings')
      ->select('website_title', 'support_email', 'support_contact', 'address', 'latitude', 'longitude')
      ->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.information', ['data' => $data]);
  }

  public function updateInfo(Request $request)
  {
    $rules = [
      'website_title' => 'required',
      'support_email' => 'required',
      'support_contact' => 'required',
      'address' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->update([
      'website_title' => $request->website_title,
      'support_email' => $request->support_email,
      'support_contact' => $request->support_contact,
      'address' => $request->address,
      'latitude' => $request->latitude,
      'longitude' => $request->longitude
    ]);

    session()->flash('success', 'Information updated successfully!');

    return redirect()->back();
  }


  public function currency()
  {
    $data = DB::table('basic_settings')
      ->select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')
      ->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.currency', ['data' => $data]);
  }

  public function updateCurrency(CurrencyRequest $request)
  {
    DB::table('basic_settings')->update([
      'base_currency_symbol' => $request->base_currency_symbol,
      'base_currency_symbol_position' => $request->base_currency_symbol_position,
      'base_currency_text' => $request->base_currency_text,
      'base_currency_text_position' => $request->base_currency_text_position,
      'base_currency_rate' => $request->base_currency_rate
    ]);

    session()->flash('success', 'Currency updated successfully!');

    return redirect()->back();
  }


  public function appearance()
  {
    $data = DB::table('basic_settings')
      ->select('primary_color', 'secondary_color', 'breadcrumb_overlay_color', 'breadcrumb_overlay_opacity')
      ->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.appearance', ['data' => $data]);
  }

  public function updateAppearance(AppearanceRequest $request)
  {
    DB::table('basic_settings')->update([
      'primary_color' => $request->primary_color,
      'secondary_color' => $request->secondary_color,
      'breadcrumb_overlay_color' => $request->breadcrumb_overlay_color,
      'breadcrumb_overlay_opacity' => $request->breadcrumb_overlay_opacity
    ]);

    session()->flash('success', 'Appearance updated successfully!');

    return redirect()->back();
  }


  public function mailFromAdmin()
  {
    $data = DB::table('basic_settings')
      ->select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.email.mail_from_admin', ['data' => $data]);
  }

  public function updateMailFromAdmin(MailFromAdminRequest $request)
  {
    DB::table('basic_settings')->update([
      'smtp_status' => $request->smtp_status,
      'smtp_host' => $request->smtp_host,
      'smtp_port' => $request->smtp_port,
      'encryption' => $request->encryption,
      'smtp_username' => $request->smtp_username,
      'smtp_password' => $request->smtp_password,
      'from_mail' => $request->from_mail,
      'from_name' => $request->from_name
    ]);

    session()->flash('success', 'Mail info updated successfully!');

    return redirect()->back();
  }


  public function mailToAdmin()
  {
    $data = DB::table('basic_settings')->select('to_mail')->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.email.mail_to_admin', ['data' => $data]);
  }

  public function updateMailToAdmin(Request $request)
  {
    $rule = [
      'to_mail' => 'required'
    ];

    $message = [
      'to_mail.required' => 'The mail address field is required.'
    ];

    $validator = Validator::make($request->all(), $rule, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->update([
      'to_mail' => $request->to_mail
    ]);

    session()->flash('success', 'Mail info updated successfully!');

    return redirect()->back();
  }


  public function breadcrumb()
  {
    $data = DB::table('basic_settings')->select('breadcrumb')->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.breadcrumb', ['data' => $data]);
  }

  public function updateBreadcrumb(Request $request)
  {
    $breadcrumbURL = $request->breadcrumb;

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    $rule = [];
    $rule['breadcrumb'] = 'required';

    if ($request->hasFile('breadcrumb')) {
      $fileExtension = $request->file('breadcrumb')->getClientOriginalExtension();
      $rule = [
        'breadcrumb' => [
          function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
            if (!in_array($fileExtension, $allowedExtensions)) {
              $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
            }
          }
        ]
      ];
    }

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    // first, get the breadcrumb from db
    $data = DB::table('basic_settings')->select('breadcrumb')->where('uniqid', 12345)
      ->first();

    if ($request->hasFile('breadcrumb')) {
      $filename = time() . '.' . $request->file('breadcrumb')->getClientOriginalExtension();
      $directory = public_path('assets/img/');
      @mkdir($directory, 0775, true);
      $request->file('breadcrumb')->move($directory, $filename);
      @unlink(public_path('assets/img/') . $data->breadcrumb);
      $breadcrumbName = $filename;
    }

    // finally, store the breadcrumb into db
    DB::table('basic_settings')->update(['breadcrumb' => $breadcrumbName]);

    session()->flash('success', 'Breadcrumb updated successfully!');

    return redirect()->back();
  }


  public function scripts()
  {
    $data = DB::table('basic_settings')
      ->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.scripts', ['data' => $data]);
  }

  public function updateScript(Request $request)
  {
    DB::table('basic_settings')->update([
      'google_recaptcha_status' => $request->google_recaptcha_status,
      'google_recaptcha_site_key' => $request->google_recaptcha_site_key,
      'google_recaptcha_secret_key' => $request->google_recaptcha_secret_key,
      'is_disqus' => $request->is_disqus,
      'disqus_shortname' => $request->disqus_shortname,
      'is_tawkto' => $request->is_tawkto,
      'tawkto_property_id' => $request->tawkto_property_id,
      'is_whatsapp' => $request->is_whatsapp,
      'whatsapp_number' => $request->whatsapp_number,
      'whatsapp_header_title' => $request->whatsapp_header_title,
      'whatsapp_popup_message' => Purifier::clean($request->whatsapp_popup_message, 'youtube'),
      'whatsapp_popup' => $request->whatsapp_popup,
      'facebook_login_status' => $request->facebook_login_status,
      'facebook_app_id' => $request->facebook_app_id,
      'facebook_app_secret' => $request->facebook_app_secret,
      'google_login_status' => $request->google_login_status,
      'google_client_id' => $request->google_client_id,
      'google_client_secret' => $request->google_client_secret
    ]);

    session()->flash('success', 'Plugins info updated successfully!');

    return redirect()->back();
  }


  public function maintenanceMode()
  {
    $data = DB::table('basic_settings')
      ->select('maintenance_img', 'maintenance_status', 'maintenance_msg', 'secret_path')
      ->first();

    return view('backend.basic_settings.maintenance', ['data' => $data]);
  }

  public function updateMaintenance(Request $request)
  {
    $rules = [
      'maintenance_status' => 'required',
      'maintenance_msg' => 'required'
    ];

    $message = [
      'maintenance_msg.required' => 'The maintenance message field is required.'
    ];

    $maintenanceImgURL = $request->maintenance_img;

    if ($request->hasFile('maintenance_img')) {
      $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
      $fileExtension = $request->file('maintenance_img')->getClientOriginalExtension();

      $rules['maintenance_img'] = function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
        if (!in_array($fileExtension, $allowedExtensions)) {
          $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
        }
      };
    }

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    // first, get the maintenance image from db
    $data = DB::table('basic_settings')->select('maintenance_img')
      ->where('uniqid', 12345)
      ->first();

    if ($request->hasFile('maintenance_img')) {
      $filename = time() . '.' . $request->file('maintenance_img')->getClientOriginalExtension();
      $directory = public_path('assets/img/');
      @mkdir($directory, 0775, true);
      $request->file('maintenance_img')->move($directory, $filename);
      @unlink(public_path('assets/img/') . $data->maintenance_img);
      $maintenanceImgName = $filename;
    }

    $down = "down";
    if ($request->filled('secret_path')) {
      $down .= " --secret=" . $request->secret_path;
    }

    if ($request->maintenance_status == 1) {
      @unlink(storage_path('framework/down'));
      Artisan::call($down);
    } else {
      Artisan::call('up');
    }

    DB::table('basic_settings')->update([
      'maintenance_img' => $request->hasFile('maintenance_img') ? $maintenanceImgName : $data->maintenance_img,
      'maintenance_status' => $request->maintenance_status,
      'maintenance_msg' => Purifier::clean($request->maintenance_msg, 'youtube'),
      'secret_path' => $request->secret_path
    ]);

    session()->flash('success', 'Maintenance Info updated successfully!');

    return redirect()->back();
  }


  public function footerLogo()
  {
    $data = DB::table('basic_settings')->select('footer_logo')->where('uniqid', 12345)
      ->first();

    return view('backend.basic_settings.footer_logo', ['data' => $data]);
  }

  public function updateFooterLogo(Request $request)
  {
    $footerLogoURL = $request->footer_logo;

    $allowedExtensions = array('jpg', 'jpeg', 'png', 'svg');
    $rules = [];
    $rules['footer_logo'] = 'required';
    if ($request->hasFile('footer_logo')) {
      $fileExtension = $request->file('footer_logo')->getClientOriginalExtension();
      $rules = [
        'footer_logo' => [
          function ($attribute, $value, $fail) use ($allowedExtensions, $fileExtension) {
            if (!in_array($fileExtension, $allowedExtensions)) {
              $fail('Only .jpg, .jpeg, .png and .svg file is allowed.');
            }
          }
        ]
      ];
    }



    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    // first, get the footer logo from db
    $data = DB::table('basic_settings')->select('footer_logo')->where('uniqid', 12345)
      ->first();

    if ($request->hasFile('footer_logo')) {
      $filename = time() . '.' . $request->file('footer_logo')->getClientOriginalExtension();
      $directory = public_path('assets/img/');
      @mkdir($directory, 0775, true);
      $request->file('footer_logo')->move($directory, $filename);
      @unlink(public_path('assets/img/') . $data->footer_logo);
      $footerLogoName = $filename;
    } else {
      $footerLogoName = $data->footer_logo;
    }

    // finally, store the footer logo into db
    DB::table('basic_settings')->update(['footer_logo' => $footerLogoName]);

    session()->flash('success', 'Footer logo updated successfully!');

    return redirect()->back();
  }

  public function preloader(Request $request)
  {
    $data['data'] = DB::table('basic_settings')->select('preloader_status', 'preloader')->first();
    return view('backend.basic_settings.preloader', $data);
  }

  public function updatepreloader(Request $request)
  {
    $preloader = $request->preloader;
    $allowedExts = array('jpg', 'png', 'jpeg', 'gif', 'svg');

    $rules = [
      'preloader_status' => 'required'
    ];

    if ($request->hasFile('preloader')) {
      $extPreloader = $request->file('preloader')->getClientOriginalExtension();
      $rules['preloader'] = [
        function ($attribute, $value, $fail) use ($extPreloader, $allowedExts) {
          if (!in_array($extPreloader, $allowedExts)) {
            return $fail("Only png, jpg, jpeg, gif, svg images are allowed");
          }
        }
      ];
    }

    $request->validate($rules);



    if ($request->filled('preloader')) {
      $filename = uniqid() . '.' . $extPreloader;
      @copy($preloader, public_path('assets/img/') . $filename);
    }

    $bs = DB::table('basic_settings')->first();

    DB::table('basic_settings')->update([
      'preloader' => $request->filled('preloader') ? $filename : $bs->preloader,
      'preloader_status' => $request->preloader_status
    ]);

    Session::flash('success', 'Preloader updated successfully.');
    return back();
  }
}
