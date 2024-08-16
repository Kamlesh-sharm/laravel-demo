<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('/admin')->middleware(['auth:admin', 'lfm.path'])->group(function () {
  Route::get('/rtlcheck/{langid}', 'BackEnd\LanguageController@rtlcheck')->name('admin.rtlcheck');

  // admin redirect to dashboard route
  Route::get('/change-theme', 'BackEnd\AdminController@changeTheme')->name('admin.theme.change');

  Route::get('/monthly-profit', 'BackEnd\AdminController@monthly_profit')->name('admin.monthly_profit');
  Route::get('/monthly-earning', 'BackEnd\AdminController@monthly_earning')->name('admin.monthly_earning');

  // admin redirect to dashboard route
  Route::get('/dashboard', 'BackEnd\AdminController@redirectToDashboard')->name('admin.dashboard');

  // Summernote image upload
  Route::post('/summernote/upload', 'BackEnd\SummernoteController@upload')->name('admin.summernote.upload');

  // admin profile settings route start
  Route::get('/edit_profile', 'BackEnd\AdminController@editProfile')->name('admin.edit_profile');

  Route::post('/update_profile', 'BackEnd\AdminController@updateProfile')->name('admin.update_profile');

  Route::get('/change_password', 'BackEnd\AdminController@changePassword')->name('admin.change_password');

  Route::post('/update_password', 'BackEnd\AdminController@updatePassword')->name('admin.update_password');
  // admin profile settings route end


  // admin logout attempt route
  Route::get('/logout', 'BackEnd\AdminController@logout')->name('admin.logout');


  // theme version route
  Route::group(['middleware' => 'checkpermission:Theme & Home'], function () {
    Route::get('/theme/version', 'BackEnd\BasicSettings\BasicSettingsController@themeVersion')->name('admin.theme.version');

    Route::post('/theme/update_version', 'BackEnd\BasicSettings\BasicSettingsController@updateThemeVersion')->name('admin.theme.update_version');
  });


  Route::group(['middleware' => 'checkpermission:Menu Builder'], function () {
    // Menus Builder Management Routes
    Route::get('/menu-builder', 'App\Http\Controllers\BackEnd\MenuBuilderController@index')->name('admin.menu_builder.index');
    Route::post('/menu-builder/update', 'App\Http\Controllers\BackEnd\MenuBuilderController@update')->name('admin.menu_builder.update');
  });


  // language management route start
  Route::group(['middleware' => 'checkpermission:Language Management'], function () {
    Route::get('/language_management', 'BackEnd\LanguageController@index')->name('admin.languages');

    Route::post('/language_management/store_language', 'BackEnd\LanguageController@store')->name('admin.languages.store_language');

    Route::post('/language_management/make_default_language/{id}', 'BackEnd\LanguageController@makeDefault')->name('admin.languages.make_default_language');

    Route::post('/language_management/update_language', 'BackEnd\LanguageController@update')->name('admin.languages.update_language');

    Route::get('/language_management/edit_keyword/{id}', 'BackEnd\LanguageController@editKeyword')->name('admin.languages.edit_keyword');

    Route::post('/language_management/update_keyword/{id}', 'BackEnd\LanguageController@updateKeyword')->name('admin.languages.update_keyword');

    Route::post('/language_management/delete_language/{id}', 'BackEnd\LanguageController@destroy')->name('admin.languages.delete_language');

    Route::post('add-keyword', 'BackEnd\LanguageController@addKeyword')->name('admin.language_management.add_keyword');
  });
  // language management route end


  // payment gateways management route start
  Route::group(['middleware' => 'checkpermission:Payment Gateways'], function () {
    Route::get('/payment_gateways/online_gateways', 'BackEnd\PaymentGateway\OnlineGatewayController@onlineGateways')->name('admin.payment_gateways.online_gateways');

    Route::post('/payment_gateways/update_paypal_info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePayPalInfo')->name('admin.payment_gateways.update_paypal_info');

    Route::post('/payment_gateways/update_stripe_info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateStripeInfo')->name('admin.payment_gateways.update_stripe_info');

    Route::post('/payment_gateways/update_instamojo_info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateInstamojoInfo')->name('admin.payment_gateways.update_instamojo_info');

    Route::post('/payment_gateways/update_paystack_info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaystackInfo')->name('admin.payment_gateways.update_paystack_info');

    Route::post('/payment_gateways/update_flutterwave_info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateFlutterwaveInfo')->name('admin.payment_gateways.update_flutterwave_info');

    Route::post('/payment_gateways/update_razorpay_info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateRazorpayInfo')->name('admin.payment_gateways.update_razorpay_info');

    Route::post('/payment_gateways/update_mercadopago_info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMercadoPagoInfo')->name('admin.payment_gateways.update_mercadopago_info');

    Route::post('/payment_gateways/update_mollie_info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMollieInfo')->name('admin.payment_gateways.update_mollie_info');

    Route::post('/payment_gateways/update_paytm_info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaytmInfo')->name('admin.payment_gateways.update_paytm_info');

    Route::get('/payment_gateways/offline_gateways', 'BackEnd\PaymentGateway\OfflineGatewayController@index')->name('admin.payment_gateways.offline_gateways');

    Route::post('/payment_gateways/store_offline_gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@store')->name('admin.payment_gateways.store_offline_gateway');

    Route::post('/payment_gateways/update_room_booking_status', 'BackEnd\PaymentGateway\OfflineGatewayController@updateRoomBookingStatus')->name('admin.payment_gateways.update_room_booking_status');

    Route::post('/payment_gateways/update_offline_gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@update')->name('admin.payment_gateways.update_offline_gateway');

    Route::post('/payment_gateways/delete_offline_gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@delete')->name('admin.payment_gateways.delete_offline_gateway');
  });
  // payment gateways management route end


  Route::group(['middleware' => 'checkpermission:Settings'], function () {

    //general settings routes are goes here
    Route::get('/general-settings', 'BackEnd\BasicSettings\BasicSettingsController@general_settings')->name('admin.basic_settings.general_settings');

    Route::post('/update-general-settings', 'BackEnd\BasicSettings\BasicSettingsController@update_general_setting')->name('admin.basic_settings.general_settings.update');


    // basic settings mail route start
    Route::get('/basic_settings/mail_from_admin', 'BackEnd\BasicSettings\BasicSettingsController@mailFromAdmin')->name('admin.basic_settings.mail_from_admin');

    Route::post('/basic_settings/update_mail_from_admin', 'BackEnd\BasicSettings\BasicSettingsController@updateMailFromAdmin')->name('admin.basic_settings.update_mail_from_admin');

    Route::get('/basic_settings/mail_to_admin', 'BackEnd\BasicSettings\BasicSettingsController@mailToAdmin')->name('admin.basic_settings.mail_to_admin');

    Route::post('/basic_settings/update_mail_to_admin', 'BackEnd\BasicSettings\BasicSettingsController@updateMailToAdmin')->name('admin.basic_settings.update_mail_to_admin');

    // Admin File Manager Routes
    Route::get('/file-manager', 'BackEnd\BasicSettings\BasicSettingsController@fileManager')->name('admin.file-manager');

    Route::get('/basic_settings/mail_templates', 'BackEnd\BasicSettings\MailTemplateController@mailTemplates')->name('admin.basic_settings.mail_templates');

    Route::get('/basic_settings/edit_mail_template/{id}', 'BackEnd\BasicSettings\MailTemplateController@editMailTemplate')->name('admin.basic_settings.edit_mail_template');

    Route::post('/basic_settings/update_mail_template/{id}', 'BackEnd\BasicSettings\MailTemplateController@updateMailTemplate')->name('admin.basic_settings.update_mail_template');
    // basic settings mail route end

    // basic settings social-links route start
    Route::get('/basic_settings/social_links', 'BackEnd\BasicSettings\SocialLinkController@socialLinks')->name('admin.basic_settings.social_links');

    Route::post('/basic_settings/store_social_link', 'BackEnd\BasicSettings\SocialLinkController@storeSocialLink')->name('admin.basic_settings.store_social_link');

    Route::get('/basic_settings/edit_social_link/{id}', 'BackEnd\BasicSettings\SocialLinkController@editSocialLink')->name('admin.basic_settings.edit_social_link');

    Route::post('/basic_settings/update_social_link', 'BackEnd\BasicSettings\SocialLinkController@updateSocialLink')->name('admin.basic_settings.update_social_link');

    Route::post('/basic_settings/delete_social_link', 'BackEnd\BasicSettings\SocialLinkController@deleteSocialLink')->name('admin.basic_settings.delete_social_link');
    // basic settings social-links route end

    // basic settings breadcrumb route
    Route::get('/basic_settings/breadcrumb', 'BackEnd\BasicSettings\BasicSettingsController@breadcrumb')->name('admin.basic_settings.breadcrumb');

    Route::post('/basic_settings/update_breadcrumb', 'BackEnd\BasicSettings\BasicSettingsController@updateBreadcrumb')->name('admin.basic_settings.update_breadcrumb');

    // basic settings page-headings route
    Route::get('/basic_settings/page_headings', 'BackEnd\BasicSettings\PageHeadingController@pageHeadings')->name('admin.basic_settings.page_headings');

    Route::post('/basic_settings/update_page_headings', 'BackEnd\BasicSettings\PageHeadingController@updatePageHeadings')->name('admin.basic_settings.update_page_headings');

    // basic settings scripts route
    Route::get('/basic_settings/scripts', 'BackEnd\BasicSettings\BasicSettingsController@scripts')->name('admin.basic_settings.scripts');

    Route::post('/basic_settings/update_script', 'BackEnd\BasicSettings\BasicSettingsController@updateScript')->name('admin.basic_settings.update_script');

    // basic settings seo route
    Route::get('/basic_settings/seo', 'BackEnd\BasicSettings\SEOController@seo')->name('admin.basic_settings.seo');

    Route::post('/basic_settings/update_seo_informations', 'BackEnd\BasicSettings\SEOController@updateSEO')->name('admin.basic_settings.update_seo_informations');

    // basic settings maintenance-mode route
    Route::get('/basic_settings/maintenance_mode', 'BackEnd\BasicSettings\BasicSettingsController@maintenanceMode')->name('admin.basic_settings.maintenance_mode');

    Route::post('/basic_settings/update_maintenance', 'BackEnd\BasicSettings\BasicSettingsController@updateMaintenance')->name('admin.basic_settings.update_maintenance');

    // basic settings cookie-alert route
    Route::get('/basic_settings/cookie_alert', 'BackEnd\BasicSettings\CookieAlertController@cookieAlert')->name('admin.basic_settings.cookie_alert');

    Route::post('/basic_settings/update_cookie_alert/{language}', 'BackEnd\BasicSettings\CookieAlertController@updateCookieAlert')->name('admin.basic_settings.update_cookie_alert');

    // basic settings footer-logo route
    Route::get('/basic_settings/footer_logo', 'BackEnd\BasicSettings\BasicSettingsController@footerLogo')->name('admin.basic_settings.footer_logo');

    Route::post('/basic_settings/update_footer_logo', 'BackEnd\BasicSettings\BasicSettingsController@updateFooterLogo')->name('admin.basic_settings.update_footer_logo');
  });


  Route::group(['middleware' => 'checkpermission:Home Page Sections'], function () {
    // home page hero-section static-version route
    Route::get('/home_page/hero/static_version', 'BackEnd\HomePage\HeroStaticController@staticVersion')->name('admin.home_page.hero.static_version');

    Route::post('/home_page/hero/static_version/update_static_info/{language}', 'BackEnd\HomePage\HeroStaticController@updateStaticInfo')->name('admin.home_page.hero.update_static_info');

    // home page hero-section slider-version route start
    Route::get('/home_page/hero/slider_version', 'BackEnd\HomePage\HeroSliderController@sliderVersion')->name('admin.home_page.hero.slider_version');

    Route::get('/home_page/hero/slider_version/create_slider', 'BackEnd\HomePage\HeroSliderController@createSlider')->name('admin.home_page.hero.create_slider');

    Route::post('/home_page/hero/slider_version/store_slider_info', 'BackEnd\HomePage\HeroSliderController@storeSliderInfo')->name('admin.home_page.hero.store_slider_info');

    Route::get('/home_page/hero/slider_version/edit_slider/{id}', 'BackEnd\HomePage\HeroSliderController@editSlider')->name('admin.home_page.hero.edit_slider');

    Route::put('/home_page/hero/slider_version/update_slider_info/{id}', 'BackEnd\HomePage\HeroSliderController@updateSliderInfo')->name('admin.home_page.hero.update_slider_info');

    Route::post('/home_page/hero/slider_version/delete_slider', 'BackEnd\HomePage\HeroSliderController@deleteSlider')->name('admin.home_page.hero.delete_slider');
    // home page hero-section slider-version route end

    // home page hero-section video-version route
    Route::get('/home_page/hero/video_version', 'BackEnd\HomePage\HeroVideoController@videoVersion')->name('admin.home_page.hero.video_version');

    Route::post('/home_page/hero/video_version/update_video_info', 'BackEnd\HomePage\HeroVideoController@updateVideoInfo')->name('admin.home_page.hero.update_video_info');

    // home page intro-section route start
    Route::get('/home_page/intro_section', 'BackEnd\HomePage\IntroSectionController@introSection')->name('admin.home_page.intro_section');

    Route::post('/home_page/update_intro_section/{language}', 'BackEnd\HomePage\IntroSectionController@updateIntroInfo')->name('admin.home_page.update_intro_section');

    Route::get('/home_page/intro_section/create_count_info', 'BackEnd\HomePage\IntroSectionController@createCountInfo')->name('admin.home_page.intro_section.create_count_info');

    Route::post('/home_page/intro_section/store_count_info', 'BackEnd\HomePage\IntroSectionController@storeCountInfo')->name('admin.home_page.intro_section.store_count_info');

    Route::get('/home_page/intro_section/edit_count_info/{id}', 'BackEnd\HomePage\IntroSectionController@editCountInfo')->name('admin.home_page.intro_section.edit_count_info');

    Route::put('/home_page/intro_section/update_count_info/{id}', 'BackEnd\HomePage\IntroSectionController@updateCountInfo')->name('admin.home_page.intro_section.update_count_info');

    Route::post('/home_page/intro_section/delete_count_info', 'BackEnd\HomePage\IntroSectionController@deleteCountInfo')->name('admin.home_page.intro_section.delete_count_info');
    // home page intro-section route end

    // home page section-heading route start
    Route::get('/home_page/room_section', 'BackEnd\HomePage\SectionHeadingController@roomSection')->name('admin.home_page.room_section');

    Route::post('/home_page/update_room_section/{language}', 'BackEnd\HomePage\SectionHeadingController@updateRoomSection')->name('admin.home_page.update_room_section');

    Route::get('/home_page/service_section', 'BackEnd\HomePage\SectionHeadingController@serviceSection')->name('admin.home_page.service_section');

    Route::post('/home_page/update_service_section/{language}', 'BackEnd\HomePage\SectionHeadingController@updateServiceSection')->name('admin.home_page.update_service_section');

    Route::get('/home_page/booking_section', 'BackEnd\HomePage\SectionHeadingController@bookingSection')->name('admin.home_page.booking_section');

    Route::post('/home_page/update_booking_section/{language}', 'BackEnd\HomePage\SectionHeadingController@updateBookingSection')->name('admin.home_page.update_booking_section');

    Route::get('/home_page/package_section', 'BackEnd\HomePage\SectionHeadingController@packageSection')->name('admin.home_page.package_section');

    Route::post('/home_page/update_package_section/{language}', 'BackEnd\HomePage\SectionHeadingController@updatePackageSection')->name('admin.home_page.update_package_section');

    Route::get('/home_page/facility_section', 'BackEnd\HomePage\SectionHeadingController@facilitySection')->name('admin.home_page.facility_section');

    Route::post('/home_page/update_facility_section/{language}', 'BackEnd\HomePage\SectionHeadingController@updateFacilitySection')->name('admin.home_page.update_facility_section');
    // home page section-heading route end

    // home page facility-section->facilities route start
    Route::get('/home_page/facility_section/create_facility', 'BackEnd\HomePage\FacilityController@createFacility')->name('admin.home_page.facility_section.create_facility');

    Route::post('/home_page/facility_section/store_facility/{language}', 'BackEnd\HomePage\FacilityController@storeFacility')->name('admin.home_page.facility_section.store_facility');

    Route::get('/home_page/facility_section/edit_facility/{id}', 'BackEnd\HomePage\FacilityController@editFacility')->name('admin.home_page.facility_section.edit_facility');

    Route::post('/home_page/facility_section/update_facility/{id}', 'BackEnd\HomePage\FacilityController@updateFacility')->name('admin.home_page.facility_section.update_facility');

    Route::post('/home_page/facility_section/delete_facility', 'BackEnd\HomePage\FacilityController@deleteFacility')->name('admin.home_page.facility_section.delete_facility');
    // home page facility-section->facilities route end

    // home page section-heading route start
    Route::get('/home_page/testimonial_section', 'BackEnd\HomePage\SectionHeadingController@testimonialSection')->name('admin.home_page.testimonial_section');

    Route::post('/home_page/update_testimonial_section/{language}', 'BackEnd\HomePage\SectionHeadingController@updateTestimonialSection')->name('admin.home_page.update_testimonial_section');
    // home page section-heading route end

    // home page testimonial-section->testimonials route start
    Route::get('/home_page/testimonial_section/create_testimonial', 'BackEnd\HomePage\TestimonialController@createTestimonial')->name('admin.home_page.testimonial_section.create_testimonial');

    Route::post('/home_page/testimonial_section/store_testimonial', 'BackEnd\HomePage\TestimonialController@storeTestimonial')->name('admin.home_page.testimonial_section.store_testimonial');

    Route::get('/home_page/testimonial_section/edit_testimonial/{id}', 'BackEnd\HomePage\TestimonialController@editTestimonial')->name('admin.home_page.testimonial_section.edit_testimonial');

    Route::post('/home_page/testimonial_section/update_testimonial/{id}', 'BackEnd\HomePage\TestimonialController@updateTestimonial')->name('admin.home_page.testimonial_section.update_testimonial');

    Route::post('/home_page/testimonial_section/delete_testimonial', 'BackEnd\HomePage\TestimonialController@deleteTestimonial')->name('admin.home_page.testimonial_section.delete_testimonial');
    // home page testimonial-section->testimonials route end

    // home page brand-section route start
    Route::get('/home_page/brand_section', 'BackEnd\HomePage\BrandSectionController@brandSection')->name('admin.home_page.brand_section');

    Route::post('/home_page/brand_section/store_brand/{language}', 'BackEnd\HomePage\BrandSectionController@storeBrand')->name('admin.home_page.brand_section.store_brand');

    Route::post('/home_page/brand_section/update_brand', 'BackEnd\HomePage\BrandSectionController@updateBrand')->name('admin.home_page.brand_section.update_brand');

    Route::post('/home_page/brand_section/delete_brand', 'BackEnd\HomePage\BrandSectionController@deleteBrand')->name('admin.home_page.brand_section.delete_brand');
    // home page brand-section route end

    // home page section-heading route start
    Route::get('/home_page/faq_section', 'BackEnd\HomePage\SectionHeadingController@faqSection')->name('admin.home_page.faq_section');

    Route::post('/home_page/update_faq_section/{language}', 'BackEnd\HomePage\SectionHeadingController@updateFAQSection')->name('admin.home_page.update_faq_section');

    Route::get('/home_page/blog_section', 'BackEnd\HomePage\SectionHeadingController@blogSection')->name('admin.home_page.blog_section');

    Route::post('/home_page/update_blog_section/{language}', 'BackEnd\HomePage\SectionHeadingController@updateBlogSection')->name('admin.home_page.update_blog_section');


    // Admin Section Customization Routes
    Route::get('/sections', 'BackEnd\HomePage\SectionsController@sections')->name('admin.sections.index');
    Route::post('/sections/update', 'BackEnd\HomePage\SectionsController@updatesections')->name('admin.sections.update');
  });


  // rooms management route start
  Route::group(['middleware' => 'checkpermission:Rooms Management'], function () {
    Route::get('/rooms_management/settings', 'BackEnd\RoomController@settings')->name('admin.rooms_management.settings');

    Route::post('/rooms_management/update_settings', 'BackEnd\RoomController@updateSettings')->name('admin.rooms_management.update_settings');

    Route::get('/rooms_management/coupons', 'BackEnd\RoomController@coupons')->name('admin.rooms_management.coupons');

    Route::post('/rooms_management/store-coupon', 'BackEnd\RoomController@storeCoupon')->name('admin.rooms_management.store_coupon');

    Route::post('/rooms_management/update-coupon', 'BackEnd\RoomController@updateCoupon')->name('admin.rooms_management.update_coupon');

    Route::post('/rooms_management/delete-coupon/{id}', 'BackEnd\RoomController@destroyCoupon')->name('admin.rooms_management.delete_coupon');

    Route::get('/rooms_management/amenities', 'BackEnd\RoomController@amenities')->name('admin.rooms_management.amenities');

    Route::post('/rooms_management/store_amenity', 'BackEnd\RoomController@storeAmenity')->name('admin.rooms_management.store_amenity');

    Route::post('/rooms_management/update_amenity', 'BackEnd\RoomController@updateAmenity')->name('admin.rooms_management.update_amenity');

    Route::post('/rooms_management/delete_amenity', 'BackEnd\RoomController@deleteAmenity')->name('admin.rooms_management.delete_amenity');

    Route::post('/rooms_management/bulk_delete_amenity', 'BackEnd\RoomController@bulkDeleteAmenity')->name('admin.rooms_management.bulk_delete_amenity');

    Route::get('/rooms_management/categories', 'BackEnd\RoomController@categories')->name('admin.rooms_management.categories');

    Route::post('/rooms_management/store_category', 'BackEnd\RoomController@storeCategory')->name('admin.rooms_management.store_category');

    Route::post('/rooms_management/update_category', 'BackEnd\RoomController@updateCategory')->name('admin.rooms_management.update_category');

    Route::post('/rooms_management/delete_category', 'BackEnd\RoomController@deleteCategory')->name('admin.rooms_management.delete_category');

    Route::post('/rooms_management/bulk_delete_category', 'BackEnd\RoomController@bulkDeleteCategory')->name('admin.rooms_management.bulk_delete_category');

    Route::get('/rooms_management/rooms', 'BackEnd\RoomController@rooms')->name('admin.rooms_management.rooms');

    //sliders images
    Route::post('/rooms_management/images-store', 'BackEnd\RoomController@gallerystore')->name('admin.rooms_management.imagesstore');
    Route::post('rooms_management/room-imagermv', 'BackEnd\RoomController@imagermv')->name('admin.rooms_management.imagermv');

    Route::post('rooms_management/room-img-dbrmv', 'BackEnd\RoomController@imagedbrmv')->name('admin.rooms_management.imgdbrmv');
    Route::get('rooms_management/room-images/{id}', 'BackEnd\RoomController@images')->name('admin.rooms_management.images');
    //sliders images end

    Route::get('/rooms_management/create_room', 'BackEnd\RoomController@createRoom')->name('admin.rooms_management.create_room');

    Route::post('/rooms_management/store_room', 'BackEnd\RoomController@storeRoom')->name('admin.rooms_management.store_room');

    Route::post('/rooms_management/update_featured_room', 'BackEnd\RoomController@updateFeaturedRoom')->name('admin.rooms_management.update_featured_room');

    Route::get('/rooms_management/edit_room/{id}', 'BackEnd\RoomController@editRoom')->name('admin.rooms_management.edit_room');

    Route::get('/rooms_management/slider_images/{id}', 'BackEnd\RoomController@getSliderImages');

    Route::post('/rooms_management/update_room/{id}', 'BackEnd\RoomController@updateRoom')->name('admin.rooms_management.update_room');

    Route::post('/rooms_management/delete_room', 'BackEnd\RoomController@deleteRoom')->name('admin.rooms_management.delete_room');

    Route::post('/rooms_management/bulk_delete_room', 'BackEnd\RoomController@bulkDeleteRoom')->name('admin.rooms_management.bulk_delete_room');
  });
  // rooms management route end


  // Room Bookings Routes
  Route::group(['middleware' => 'checkpermission:Room Bookings'], function () {
    Route::get('/room_bookings/all_bookings', 'BackEnd\RoomController@bookings')->name('admin.room_bookings.all_bookings');

    Route::get('/room_bookings/paid_bookings', 'BackEnd\RoomController@bookings')->name('admin.room_bookings.paid_bookings');

    Route::get('/room_bookings/unpaid_bookings', 'BackEnd\RoomController@bookings')->name('admin.room_bookings.unpaid_bookings');

    Route::post('/room_bookings/update_payment_status', 'BackEnd\RoomController@updatePaymentStatus')->name('admin.room_bookings.update_payment_status');

    Route::get('/room_bookings/booking_details_and_edit/{id}', 'BackEnd\RoomController@editBookingDetails')->name('admin.room_bookings.booking_details_and_edit');

    Route::get('/room_bookings/booking_details/{id}', 'BackEnd\RoomController@bookingDetails')->name('admin.room_bookings.booking_details');

    Route::post('/room_bookings/update_booking', 'BackEnd\RoomController@updateBooking')->name('admin.room_bookings.update_booking');

    Route::post('/room_bookings/send_mail', 'BackEnd\RoomController@sendMail')->name('admin.room_bookings.send_mail');

    Route::post('/room_bookings/delete_booking/{id}', 'BackEnd\RoomController@deleteBooking')->name('admin.room_bookings.delete_booking');

    Route::post('/room_bookings/bulk_delete_booking', 'BackEnd\RoomController@bulkDeleteBooking')->name('admin.room_bookings.bulk_delete_booking');

    Route::get('/room_bookings/get_booked_dates', 'BackEnd\RoomController@bookedDates')->name('admin.room_bookings.get_booked_dates');

    Route::get('/room_bookings/booking_form', 'BackEnd\RoomController@bookingForm')->name('admin.room_bookings.booking_form');

    Route::post('/room_bookings/make_booking', 'BackEnd\RoomController@makeBooking')->name('admin.room_bookings.make_booking');
  });


  // services management route start
  Route::group(['middleware' => 'checkpermission:Services Management'], function () {
    Route::get('/services_management', 'BackEnd\ServiceController@services')->name('admin.services_management');

    Route::get('/services_management/create_service', 'BackEnd\ServiceController@createService')->name('admin.services_management.create_service');

    Route::post('/services_management/store_service', 'BackEnd\ServiceController@storeService')->name('admin.services_management.store_service');

    Route::post('/services_management/update_featured_service', 'BackEnd\ServiceController@updateFeaturedService')->name('admin.services_management.update_featured_service');

    Route::get('/services_management/edit_service/{id}', 'BackEnd\ServiceController@editService')->name('admin.services_management.edit_service');

    Route::post('/services_management/update_service/{id}', 'BackEnd\ServiceController@updateService')->name('admin.services_management.update_service');

    Route::post('/services_management/delete_service', 'BackEnd\ServiceController@deleteService')->name('admin.services_management.delete_service');

    Route::post('/services_management/bulk_delete_service', 'BackEnd\ServiceController@bulkDeleteService')->name('admin.services_management.bulk_delete_service');
  });
  // services management route end


  // custom pages route start
  Route::group(['middleware' => 'checkpermission:Custom Pages'], function () {
    Route::get('/pages', 'App\Http\Controllers\BackEnd\PageController@index')->name('admin.page.index');
    Route::get('/page/create', 'App\Http\Controllers\BackEnd\PageController@create')->name('admin.page.create');
    Route::post('/page/store', 'App\Http\Controllers\BackEnd\PageController@store')->name('admin.page.store');
    Route::get('/page/{menuID}/edit', 'App\Http\Controllers\BackEnd\PageController@edit')->name('admin.page.edit');
    Route::post('/page/update', 'App\Http\Controllers\BackEnd\PageController@update')->name('admin.page.update');
    Route::post('/page/delete', 'App\Http\Controllers\BackEnd\PageController@delete')->name('admin.page.delete');
    Route::post('/page/bulk-delete', 'App\Http\Controllers\BackEnd\PageController@bulkDelete')->name('admin.page.bulk.delete');
  });
  // custom pages route end


  // blogs management route start
  Route::group(['middleware' => 'checkpermission:Blogs Management'], function () {
    Route::get('/blogs_management/categories', 'BackEnd\BlogController@blogCategories')->name('admin.blogs_management.categories');

    Route::post('/blogs_management/store_category', 'BackEnd\BlogController@storeCategory')->name('admin.blogs_management.store_category');

    Route::post('/blogs_management/update_category', 'BackEnd\BlogController@updateCategory')->name('admin.blogs_management.update_category');

    Route::post('/blogs_management/delete_category', 'BackEnd\BlogController@deleteCategory')->name('admin.blogs_management.delete_category');

    Route::post('/blogs_management/bulk_delete_category', 'BackEnd\BlogController@bulkDeleteCategory')->name('admin.blogs_management.bulk_delete_category');

    Route::get('/blogs_management/blogs', 'BackEnd\BlogController@blogs')->name('admin.blogs_management.blogs');

    Route::get('/blogs_management/create_blog', 'BackEnd\BlogController@createBlog')->name('admin.blogs_management.create_blog');

    Route::post('/blogs_management/store_blog', 'BackEnd\BlogController@storeBlog')->name('admin.blogs_management.store_blog');

    Route::get('/blogs_management/edit_blog/{id}', 'BackEnd\BlogController@editBlog')->name('admin.blogs_management.edit_blog');

    Route::post('/blogs_management/update_blog/{id}', 'BackEnd\BlogController@updateBlog')->name('admin.blogs_management.update_blog');

    Route::post('/blogs_management/delete_blog', 'BackEnd\BlogController@deleteBlog')->name('admin.blogs_management.delete_blog');

    Route::post('/blogs_management/bulk_delete_blog', 'BackEnd\BlogController@bulkDeleteBlog')->name('admin.blogs_management.bulk_delete_blog');
  });
  // blogs management route end


  // gallery management route start
  Route::group(['middleware' => 'checkpermission:Gallery Management'], function () {
    Route::get('/gallery_management/categories', 'BackEnd\GalleryController@categories')->name('admin.gallery_management.categories');

    Route::post('/gallery_management/store_category', 'BackEnd\GalleryController@storeCategory')->name('admin.gallery_management.store_category');

    Route::post('/gallery_management/update_category', 'BackEnd\GalleryController@updateCategory')->name('admin.gallery_management.update_category');

    Route::post('/gallery_management/delete_category', 'BackEnd\GalleryController@deleteCategory')->name('admin.gallery_management.delete_category');

    Route::post('/gallery_management/bulk_delete_category', 'BackEnd\GalleryController@bulkDeleteCategory')->name('admin.gallery_management.bulk_delete_category');

    Route::get('/gallery_management/images', 'BackEnd\GalleryController@index')->name('admin.gallery_management.images');

    Route::post('/gallery_management/store_gallery_info/{language}', 'BackEnd\GalleryController@storeInfo')->name('admin.gallery_management.store_gallery_info');

    Route::post('/gallery_management/update_gallery_info', 'BackEnd\GalleryController@updateInfo')->name('admin.gallery_management.update_gallery_info');

    Route::post('/gallery_management/delete_gallery_info', 'BackEnd\GalleryController@deleteInfo')->name('admin.gallery_management.delete_gallery_info');

    Route::post('/gallery_management/bulk_delete_gallery_info', 'BackEnd\GalleryController@bulkDeleteInfo')->name('admin.gallery_management.bulk_delete_gallery_info');
  });
  // gallery management route end


  // faq management route start
  Route::group(['middleware' => 'checkpermission:FAQ Management'], function () {
    Route::get('/faq_management', 'BackEnd\FAQController@index')->name('admin.faq_management');

    Route::post('/faq_management/store_faq', 'BackEnd\FAQController@store')->name('admin.faq_management.store_faq');

    Route::post('/faq_management/update_faq', 'BackEnd\FAQController@update')->name('admin.faq_management.update_faq');

    Route::post('/faq_management/delete_faq', 'BackEnd\FAQController@delete')->name('admin.faq_management.delete_faq');

    Route::post('/faq_management/bulk_delete_faq', 'BackEnd\FAQController@bulkDelete')->name('admin.faq_management.bulk_delete_faq');
  });
  // faq management route end


  // packages management route start
  Route::group(['middleware' => 'checkpermission:Packages Management'], function () {
    Route::get('/packages_management/settings', 'BackEnd\PackageController@settings')->name('admin.packages_management.settings');

    Route::post('/packages_management/update_settings', 'BackEnd\PackageController@updateSettings')->name('admin.packages_management.update_settings');

    Route::get('/packages_management/coupons', 'BackEnd\PackageController@coupons')->name('admin.packages_management.coupons');

    Route::post('/packages_management/store-coupon', 'BackEnd\PackageController@storeCoupon')->name('admin.packages_management.store_coupon');

    Route::post('/packages_management/update-coupon', 'BackEnd\PackageController@updateCoupon')->name('admin.packages_management.update_coupon');

    Route::post('/packages_management/delete-coupon/{id}', 'BackEnd\PackageController@destroyCoupon')->name('admin.packages_management.delete_coupon');

    Route::get('/packages_management/categories', 'BackEnd\PackageController@categories')->name('admin.packages_management.categories');

    Route::post('/packages_management/store_category', 'BackEnd\PackageController@storeCategory')->name('admin.packages_management.store_category');

    Route::post('/packages_management/update_category', 'BackEnd\PackageController@updateCategory')->name('admin.packages_management.update_category');

    Route::post('/packages_management/delete_category', 'BackEnd\PackageController@deleteCategory')->name('admin.packages_management.delete_category');

    Route::post('/packages_management/bulk_delete_category', 'BackEnd\PackageController@bulkDeleteCategory')->name('admin.packages_management.bulk_delete_category');

    Route::get('/packages_management/packages', 'BackEnd\PackageController@packages')->name('admin.packages_management.packages');

    Route::get('/packages_management/create_package', 'BackEnd\PackageController@createPackage')->name('admin.packages_management.create_package');

    //sliders images
    Route::post('/packages_management/images-store', 'BackEnd\PackageController@gallerystore')->name('admin.packages_management.imagesstore');
    Route::post('room-imagermv', 'BackEnd\PackageController@imagermv')->name('admin.packages_management.imagermv');

    Route::post('room-img-dbrmv', 'BackEnd\PackageController@imagedbrmv')->name('admin.packages_management.imgdbrmv');
    Route::get('room-images/{id}', 'BackEnd\PackageController@images')->name('admin.packages_management.images');
    //sliders images end

    Route::post('/packages_management/store_package', 'BackEnd\PackageController@storePackage')->name('admin.packages_management.store_package');

    Route::post('/packages_management/update_featured_package', 'BackEnd\PackageController@updateFeaturedPackage')->name('admin.packages_management.update_featured_package');

    Route::get('/packages_management/edit_package/{id}', 'BackEnd\PackageController@editPackage')->name('admin.packages_management.edit_package');

    Route::get('/packages_management/slider_images/{id}', 'BackEnd\PackageController@getSliderImages');

    Route::post('/packages_management/update_package/{id}', 'BackEnd\PackageController@updatePackage')->name('admin.packages_management.update_package');

    Route::post('/packages_management/delete_package', 'BackEnd\PackageController@deletePackage')->name('admin.packages_management.delete_package');

    Route::post('/packages_management/bulk_delete_package', 'BackEnd\PackageController@bulkDeletePackage')->name('admin.packages_management.bulk_delete_package');

    Route::post('/packages_management/store_location', 'BackEnd\PackageController@storeLocation')->name('admin.packages_management.store_location');

    Route::get('/packages_management/view_locations/{package_id}', 'BackEnd\PackageController@viewLocations')->name('admin.packages_management.view_locations');

    Route::post('/packages_management/update_location', 'BackEnd\PackageController@updateLocation')->name('admin.packages_management.update_location');

    Route::post('/packages_management/delete_location', 'BackEnd\PackageController@deleteLocation')->name('admin.packages_management.delete_location');

    Route::post('/packages_management/bulk_delete_location', 'BackEnd\PackageController@bulkDeleteLocation')->name('admin.packages_management.bulk_delete_location');

    Route::post('/packages_management/store_daywise_plan', 'BackEnd\PackageController@storeDaywisePlan')->name('admin.packages_management.store_daywise_plan');

    Route::post('/packages_management/store_timewise_plan', 'BackEnd\PackageController@storeTimewisePlan')->name('admin.packages_management.store_timewise_plan');

    Route::get('/packages_management/view_plans/{package_id}', 'BackEnd\PackageController@viewPlans')->name('admin.packages_management.view_plans');

    Route::post('/packages_management/update_daywise_plan', 'BackEnd\PackageController@updateDaywisePlan')->name('admin.packages_management.update_daywise_plan');

    Route::post('/packages_management/update_timewise_plan', 'BackEnd\PackageController@updateTimewisePlan')->name('admin.packages_management.update_timewise_plan');

    Route::post('/packages_management/delete_plan', 'BackEnd\PackageController@deletePlan')->name('admin.packages_management.delete_plan');

    Route::post('/packages_management/bulk_delete_plan', 'BackEnd\PackageController@bulkDeletePlan')->name('admin.packages_management.bulk_delete_plan');
  });
  // packages management route end


  // Package Bookings Routes
  Route::group(['middleware' => 'checkpermission:Package Bookings'], function () {
    Route::get('/package_bookings/all_bookings', 'BackEnd\PackageController@bookings')->name('admin.package_bookings.all_bookings');

    Route::get('/package_bookings/paid_bookings', 'BackEnd\PackageController@bookings')->name('admin.package_bookings.paid_bookings');

    Route::get('/package_bookings/unpaid_bookings', 'BackEnd\PackageController@bookings')->name('admin.package_bookings.unpaid_bookings');

    Route::post('/package_bookings/update_payment_status', 'BackEnd\PackageController@updatePaymentStatus')->name('admin.package_bookings.update_payment_status');

    Route::get('/package_bookings/booking_details/{id}', 'BackEnd\PackageController@bookingDetails')->name('admin.package_bookings.booking_details');

    Route::post('/package_bookings/send_mail', 'BackEnd\PackageController@sendMail')->name('admin.package_bookings.send_mail');

    Route::post('/package_bookings/delete_booking/{id}', 'BackEnd\PackageController@deleteBooking')->name('admin.package_bookings.delete_booking');

    Route::post('/package_bookings/bulk_delete_booking', 'BackEnd\PackageController@bulkDeleteBooking')->name('admin.package_bookings.bulk_delete_booking');
  });


  // footer route start
  Route::group(['middleware' => 'checkpermission:Footer'], function () {
    Route::get('/footer/text', 'BackEnd\FooterController@footerText')->name('admin.footer.text');

    Route::post('/footer/update_footer_info/{language}', 'BackEnd\FooterController@updateFooterInfo')->name('admin.footer.update_footer_info');

    Route::get('/footer/quick_links', 'BackEnd\FooterController@quickLinks')->name('admin.footer.quick_links');

    Route::post('/footer/store_quick_link/{language}', 'BackEnd\FooterController@storeQuickLink')->name('admin.footer.store_quick_link');

    Route::post('/footer/update_quick_link', 'BackEnd\FooterController@updateQuickLink')->name('admin.footer.update_quick_link');

    Route::post('/footer/delete_quick_link', 'BackEnd\FooterController@deleteQuickLink')->name('admin.footer.delete_quick_link');
  });
  // footer route end


  // Announcement Popup Routes
  Route::group(['middleware' => 'checkpermission:Announcement Popup'], function () {
    Route::get('popups', 'App\Http\Controllers\BackEnd\PopupController@index')->name('admin.popup.index');
    Route::get('popup/types', 'App\Http\Controllers\BackEnd\PopupController@types')->name('admin.popup.types');
    Route::get('popup/{id}/edit', 'App\Http\Controllers\BackEnd\PopupController@edit')->name('admin.popup.edit');
    Route::get('popup/create', 'App\Http\Controllers\BackEnd\PopupController@create')->name('admin.popup.create');
    Route::post('popup/store', 'App\Http\Controllers\BackEnd\PopupController@store')->name('admin.popup.store');;
    Route::post('popup/delete', 'App\Http\Controllers\BackEnd\PopupController@delete')->name('admin.popup.delete');
    Route::post('popup/bulk-delete', 'App\Http\Controllers\BackEnd\PopupController@bulkDelete')->name('admin.popup.bulk.delete');
    Route::post('popup/status', 'App\Http\Controllers\BackEnd\PopupController@status')->name('admin.popup.status');
    Route::post('popup/update', 'App\Http\Controllers\BackEnd\PopupController@update')->name('admin.popup.update');;
  });


  Route::group(['middleware' => 'checkpermission:Users Management'], function () {
    // Admin Subscriber Routes
    Route::get('/subscribers', 'App\Http\Controllers\BackEnd\SubscriberController@index')->name('admin.subscriber.index');
    Route::get('/mailsubscriber', 'App\Http\Controllers\BackEnd\SubscriberController@mailsubscriber')->name('admin.mailsubscriber');
    Route::post('/subscribers/sendmail', 'App\Http\Controllers\BackEnd\SubscriberController@subscsendmail')->name('admin.subscribers.sendmail');
    Route::post('/subscriber/delete', 'App\Http\Controllers\BackEnd\SubscriberController@delete')->name('admin.subscriber.delete');
    Route::post('/subscriber/bulk-delete', 'App\Http\Controllers\BackEnd\SubscriberController@bulkDelete')->name('admin.subscriber.bulk.delete');


    // Register User start
    Route::get('register/users', 'App\Http\Controllers\BackEnd\RegisterUserController@index')->name('admin.register.user');

    Route::get('register/users/create', 'App\Http\Controllers\BackEnd\RegisterUserController@create')->name('admin.register.create');
    Route::post('register/users/store', 'App\Http\Controllers\BackEnd\RegisterUserController@store')->name('admin.register.user.store');
    Route::get('register/users/edit/{id}', 'App\Http\Controllers\BackEnd\RegisterUserController@edit')->name('admin.register.user.edit');
    Route::post('register/users/update/{id}', 'App\Http\Controllers\BackEnd\RegisterUserController@update')->name('admin.register.user.update');

    Route::post('register/users/ban', 'App\Http\Controllers\BackEnd\RegisterUserController@userban')->name('register.user.ban');
    Route::post('register/users/email', 'App\Http\Controllers\BackEnd\RegisterUserController@emailStatus')->name('register.user.email');
    Route::get('register/user/details/{id}', 'App\Http\Controllers\BackEnd\RegisterUserController@view')->name('register.user.view');
    Route::post('register/user/delete', 'App\Http\Controllers\BackEnd\RegisterUserController@delete')->name('register.user.delete');
    Route::post('register/user/bulk-delete', 'App\Http\Controllers\BackEnd\RegisterUserController@bulkDelete')->name('register.user.bulk.delete');
    Route::get('register/user/{id}/changePassword', 'App\Http\Controllers\BackEnd\RegisterUserController@changePass')->name('register.user.changePass');
    Route::post('register/user/updatePassword', 'App\Http\Controllers\BackEnd\RegisterUserController@updatePassword')->name('register.user.updatePassword');

    Route::get('register/user/secret-login/{id}', 'App\Http\Controllers\BackEnd\RegisterUserController@secret_login')->name('register.user.secret_login');
    //Register User end


    // push notification route
    Route::prefix('/push-notification')->group(function () {
      Route::get('/settings', 'BackEnd\PushNotificationController@settings')->name('admin.user_management.push_notification.settings');

      Route::post('/update-settings', 'BackEnd\PushNotificationController@updateSettings')->name('admin.user_management.push_notification.update_settings');

      Route::get('/notification-for-visitors', 'BackEnd\PushNotificationController@writeNotification')->name('admin.user_management.push_notification.notification_for_visitors');

      Route::post('/send', 'BackEnd\PushNotificationController@sendNotification')->name('admin.user_management.push_notification.send');
    });
  });

  // vendor management route start
  Route::prefix('/vendor-management')->middleware('checkpermission:Vendors Management')->group(function () {
    Route::get('/settings', 'BackEnd\VendorManagementController@settings')->name('admin.vendor_management.settings');
    Route::post('/settings/update', 'BackEnd\VendorManagementController@update_setting')->name('admin.vendor_management.setting.update');

    Route::get('/add-vendor', 'BackEnd\VendorManagementController@add')->name('admin.vendor_management.add_vendor');
    Route::post('/save-vendor', 'BackEnd\VendorManagementController@create')->name('admin.vendor_management.save-vendor');

    Route::get('/registered-vendors', 'BackEnd\VendorManagementController@index')->name('admin.vendor_management.registered_vendor');

    Route::prefix('/vendor/{id}')->group(function () {

      Route::post('/update-account-status', 'BackEnd\VendorManagementController@updateAccountStatus')->name('admin.vendor_management.vendor.update_account_status');

      Route::post('/update-email-status', 'BackEnd\VendorManagementController@updateEmailStatus')->name('admin.vendor_management.vendor.update_email_status');

      Route::get('/details', 'BackEnd\VendorManagementController@show')->name('admin.vendor_management.vendor_details');

      Route::get('/edit', 'BackEnd\VendorManagementController@edit')->name('admin.edit_management.vendor_edit');

      Route::post('/update', 'BackEnd\VendorManagementController@update')->name('admin.vendor_management.vendor.update_vendor');

      Route::post('/update/vendor/balance', 'BackEnd\VendorManagementController@update_vendor_balance')->name('admin.vendor_management.vendor.update_vendor_balance');

      Route::get('/change-password', 'BackEnd\VendorManagementController@changePassword')->name('admin.vendor_management.vendor.change_password');

      Route::post('/update-password', 'BackEnd\VendorManagementController@updatePassword')->name('admin.vendor_management.vendor.update_password');

      Route::post('/delete', 'BackEnd\VendorManagementController@destroy')->name('admin.vendor_management.vendor.delete');
    });

    Route::post('/bulk-delete-vendor', 'BackEnd\VendorManagementController@bulkDestroy')->name('admin.vendor_management.bulk_delete_vendor');

    Route::get('secret-login/{id}', 'BackEnd\VendorManagementController@secret_login')->name('admin.vendor_management.secret_login');
  });
  // vendor management route start

  Route::prefix('withdraw')->middleware('checkpermission:Withdraw')->group(function () {
    Route::get('/payment-methods', 'BackEnd\WithdrawPaymentMethodController@index')->name('admin.withdraw.payment_method');
    Route::post('/payment-methods/store', 'BackEnd\WithdrawPaymentMethodController@store')->name('admin.withdraw_payment_method.store');
    Route::put('/payment-methods/update', 'BackEnd\WithdrawPaymentMethodController@update')->name('admin.withdraw_payment_method.update');
    Route::post('/payment-methods/delete/{id}', 'BackEnd\WithdrawPaymentMethodController@destroy')->name('admin.withdraw_payment_method.delete');

    Route::get('/payment-method/input', 'BackEnd\WithdrawPaymentMethodInputController@index')->name('admin.withdraw_payment_method.mange_input');
    Route::post('/payment-method/input-store', 'BackEnd\WithdrawPaymentMethodInputController@store')->name('admin.withdraw_payment_method.store_input');
    Route::get('/payment-method/input-edit/{id}', 'BackEnd\WithdrawPaymentMethodInputController@edit')->name('admin.withdraw_payment_method.edit_input');
    Route::get('/payment-method/input-edit/{id}', 'BackEnd\WithdrawPaymentMethodInputController@edit')->name('admin.withdraw_payment_method.edit_input');
    Route::post('/payment-method/input-update', 'BackEnd\WithdrawPaymentMethodInputController@update')->name('admin.withdraw_payment_method.update_input');
    Route::post('/payment-method/order-update', 'BackEnd\WithdrawPaymentMethodInputController@order_update')->name('admin.withdraw_payment_method.order_update');
    Route::get('/payment-method/input-option/{id}', 'BackEnd\WithdrawPaymentMethodInputController@get_options')->name('admin.withdraw_payment_method.options');
    Route::post('/payment-method/input-delete', 'BackEnd\WithdrawPaymentMethodInputController@delete')->name('admin.withdraw_payment_method.options_delete');

    Route::get('/withdraw-request', 'BackEnd\WithdrawController@index')->name('admin.withdraw.withdraw_request');
    Route::post('/withdraw-request/delete', 'BackEnd\WithdrawController@delete')->name('admin.witdraw.delete_withdraw');
    Route::get('/withdraw-request/approve/{id}', 'BackEnd\WithdrawController@approve')->name('admin.witdraw.approve_withdraw');


    Route::get('/withdraw-request/decline/{id}', 'BackEnd\WithdrawController@decline')->name('admin.witdraw.decline_withdraw');
  });

  Route::get('/transcation', 'BackEnd\AdminController@transcation')->name('admin.transcation')->middleware('checkpermission:Transaction');




  Route::group(['middleware' => 'checkpermission:Admins Management'], function () {
    // Admin Users Routes
    Route::get('/users', 'App\Http\Controllers\BackEnd\UserController@index')->name('admin.user.index');
    Route::post('/user/upload', 'App\Http\Controllers\BackEnd\UserController@upload')->name('admin.user.upload');
    Route::post('/user/store', 'App\Http\Controllers\BackEnd\UserController@store')->name('admin.user.store');
    Route::get('/user/{id}/edit', 'App\Http\Controllers\BackEnd\UserController@edit')->name('admin.user.edit');
    Route::post('/user/update', 'App\Http\Controllers\BackEnd\UserController@update')->name('admin.user.update');
    Route::post('/user/{id}/uploadUpdate', 'App\Http\Controllers\BackEnd\UserController@uploadUpdate')->name('admin.user.uploadUpdate');
    Route::post('/user/delete', 'App\Http\Controllers\BackEnd\UserController@delete')->name('admin.user.delete');

    // Admin Roles Routes
    Route::get('/roles', 'App\Http\Controllers\BackEnd\RoleController@index')->name('admin.role.index');
    Route::post('/role/store', 'App\Http\Controllers\BackEnd\RoleController@store')->name('admin.role.store');
    Route::post('/role/update', 'App\Http\Controllers\BackEnd\RoleController@update')->name('admin.role.update');
    Route::post('/role/delete', 'App\Http\Controllers\BackEnd\RoleController@delete')->name('admin.role.delete');
    Route::get('role/{id}/permissions/manage', 'App\Http\Controllers\BackEnd\RoleController@managePermissions')->name('admin.role.permissions.manage');
    Route::post('role/permissions/update', 'App\Http\Controllers\BackEnd\RoleController@updatePermissions')->name('admin.role.permissions.update');
  });

  #====support tickets ============

  Route::prefix('support/ticket')->middleware('checkpermission:Support Tickets')->group(function () {
    Route::get('/setting', 'BackEnd\SupportTicketController@setting')->name('admin.support_ticket.setting');
    Route::post('/setting/update', 'BackEnd\SupportTicketController@update_setting')->name('admin.support_ticket.update_setting');
    Route::get('/', 'BackEnd\SupportTicketController@index')->name('admin.support_tickets');
    Route::get('/message/{id}', 'BackEnd\SupportTicketController@message')->name('admin.support_tickets.message');
    Route::post('/zip-upload', 'BackEnd\SupportTicketController@zip_file_upload')->name('admin.support_ticket.zip_file.upload');
    Route::post('/reply/{id}', 'BackEnd\SupportTicketController@ticketreply')->name('admin.support_ticket.reply');
    Route::post('/closed/{id}', 'BackEnd\SupportTicketController@ticket_closed')->name('admin.support_ticket.close');
    Route::post('/assign-stuff/{id}', 'BackEnd\SupportTicketController@assign_stuff')->name('assign_stuff.supoort.ticket');

    Route::get('support-ticket/unassign-stuff/{id}', 'BackEnd\SupportTicketController@unassign_stuff')->name('admin.support_tickets.unassign');

    Route::post('/delete/{id}', 'BackEnd\SupportTicketController@delete')->name('admin.support_tickets.delete');

    Route::post('support-ticket/bulk/delete/', 'BackEnd\SupportTicketController@bulk_delete')->name('admin.support_tickets.bulk_delete');
  });



  // Sitemap Routes start
  Route::group(['middleware' => 'checkpermission:Sitemap'], function () {
    Route::get('/sitemap', 'App\Http\Controllers\BackEnd\SitemapController@index')->name('admin.sitemap.index');
    Route::post('/sitemap/store', 'App\Http\Controllers\BackEnd\SitemapController@store')->name('admin.sitemap.store');
    Route::get('/sitemap/{id}/update', 'App\Http\Controllers\BackEnd\SitemapController@update')->name('admin.sitemap.update');
    Route::post('/sitemap/{id}/delete', 'App\Http\Controllers\BackEnd\SitemapController@delete')->name('admin.sitemap.delete');
    Route::post('/sitemap/download', 'App\Http\Controllers\BackEnd\SitemapController@download')->name('admin.sitemap.download');
  });
  // Sitemap Routes end


  // Admin Cache Clear Routes
  Route::get('/cache-clear', 'App\Http\Controllers\BackEnd\CacheController@clear')->name('admin.cache.clear');


  // QR Code Builder Routes
  Route::group(['middleware' => 'checkpermission:QR Builder'], function () {
    Route::get('/saved/qrs', 'App\Http\Controllers\BackEnd\QrController@index')->name('admin.qrcode.index');
    Route::post('/saved/qr/delete', 'App\Http\Controllers\BackEnd\QrController@delete')->name('admin.qrcode.delete');
    Route::post('/saved/qr/bulk-delete', 'App\Http\Controllers\BackEnd\QrController@bulkDelete')->name('admin.qrcode.bulk.delete');
    Route::get('/qr-code', 'App\Http\Controllers\BackEnd\QrController@qrCode')->name('admin.qrcode');
    Route::post('/qr-code/generate', 'App\Http\Controllers\BackEnd\QrController@generate')->name('admin.qrcode.generate');
    Route::get('/qr-code/clear', 'App\Http\Controllers\BackEnd\QrController@clear')->name('admin.qrcode.clear');
    Route::post('/qr-code/save', 'App\Http\Controllers\BackEnd\QrController@save')->name('admin.qrcode.save');
  });
});
