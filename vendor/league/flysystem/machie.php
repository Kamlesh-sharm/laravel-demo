<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
  return view('errors.404');
});

/*
|--------------------------------------------------------------------------
| FrontEnd Routes
|--------------------------------------------------------------------------
*/
Route::post('/push-notification/store-endpoint', 'FrontEnd\PushNotificationController@store');

Route::get('login', 'Vendor\VendorController@login')->name('login');

Route::get('/change_language', [Controller::class, 'changeLanguage'])->name('change_language');

Route::middleware('language')->group(function () {
  Route::get('/', 'FrontEnd\HomeController@index')->name('index');
  Route::get('/about-us', 'FrontEnd\HomeController@about')->name('about');

  Route::get('/rooms', 'FrontEnd\Room\RoomController@rooms')->name('rooms');

  Route::get('/room_details/{id}/{slug}', 'FrontEnd\Room\RoomController@roomDetails')->name('room_details');
});

Route::post('/subscribe', 'FrontEnd\HomeController@subscribe')->name('subscribe');

Route::post('/room_booking/apply_coupon', 'FrontEnd\Room\RoomController@applyCoupon');
Route::get('/room_booking/remove_coupon', 'FrontEnd\Room\RoomController@remove_coupon');

Route::post('/room/store_review/{id}', 'FrontEnd\Room\RoomController@storeReview')->name('room.store_review');

Route::post('/room_booking', 'FrontEnd\Room\RoomBookingController@makeRoomBooking')->name('room_booking');

Route::get('/room_booking/paypal/notify', 'FrontEnd\Room\PayPalController@notify')->name('room_booking.paypal.notify');

Route::post('/room_booking/paytm/notify', 'FrontEnd\Room\PaytmController@notify')->name('room_booking.paytm.notify');

Route::get('/room_booking/instamojo/notify', 'FrontEnd\Room\InstamojoController@notify')->name('room_booking.instamojo.notify');

Route::get('/room_booking/paystack/notify', 'FrontEnd\Room\PaystackController@notify')->name('room_booking.paystack.notify');

Route::post('/room_booking/flutterwave/notify', 'FrontEnd\Room\FlutterwaveController@notify')->name('room_booking.flutterwave.notify');

Route::get('/room_booking/mollie/notify', 'FrontEnd\Room\MollieController@notify')->name('room_booking.mollie.notify');

Route::post('/room_booking/razorpay/notify', 'FrontEnd\Room\RazorpayController@notify')->name('room_booking.razorpay.notify');

Route::get('/room_booking/mercadopago/notify', 'FrontEnd\Room\MercadoPagoController@notify')->name('room_booking.mercadopago.notify');

Route::middleware('language')->group(function () {
  Route::get('/room_booking/complete', 'FrontEnd\Room\RoomBookingController@complete')->name('room_booking.complete');

  Route::get('/room_booking/cancel', 'FrontEnd\Room\RoomBookingController@cancel')->name('room_booking.cancel');

  Route::get('/services', 'FrontEnd\ServiceController@services')->name('services');

  Route::get('/service_details/{id}/{slug}', 'FrontEnd\ServiceController@serviceDetails')->name('service_details');

  Route::get('/blogs', 'FrontEnd\BlogController@blogs')->name('blogs');

  Route::get('/blog_details/{id}/{slug}', 'FrontEnd\BlogController@blogDetails')->name('blog_details');

  Route::get('/gallery', 'FrontEnd\GalleryController@gallery')->name('gallery');

  Route::get('/packages', 'FrontEnd\Package\PackageController@packages')->name('packages');

  Route::get('/package_details/{id}/{slug}', 'FrontEnd\Package\PackageController@packageDetails')->name('package_details');
});

Route::post('/package_booking/apply_coupon', 'FrontEnd\Package\PackageController@applyCoupon');
Route::post('/package_booking/remove_coupon', 'FrontEnd\Package\PackageController@removeCoupon');

Route::post('/package/store_review/{id}', 'FrontEnd\Package\PackageController@storeReview')->name('package.store_review');

Route::post('/package_booking', 'FrontEnd\Package\PackageBookingController@makePackageBooking')->name('package_booking');

Route::get('/package_booking/paypal/notify', 'FrontEnd\Package\PayPalController@notify')->name('package_booking.paypal.notify');

Route::get('/package_booking/stripe/notify', 'FrontEnd\Package\StripeController@notify')->name('package_booking.stripe.notify');

Route::get('/package_booking/instamojo/notify', 'FrontEnd\Package\InstamojoController@notify')->name('package_booking.instamojo.notify');

Route::get('/package_booking/paystack/notify', 'FrontEnd\Package\PaystackController@notify')->name('package_booking.paystack.notify');

Route::post('/package_booking/razorpay/notify', 'FrontEnd\Package\RazorpayController@notify')->name('package_booking.razorpay.notify');

Route::get('/package_booking/mollie/notify', 'FrontEnd\Package\MollieController@notify')->name('package_booking.mollie.notify');

Route::post('/package_booking/paytm/notify', 'FrontEnd\Package\PaytmController@notify')->name('package_booking.paytm.notify');

Route::get('/package_booking/mercadopago/notify', 'FrontEnd\Package\MercadoPagoController@notify')->name('package_booking.mercadopago.notify');

Route::post('/package_booking/flutterwave/notify', 'FrontEnd\Package\FlutterwaveController@notify')->name('package_booking.flutterwave.notify');

Route::middleware('language')->group(function () {
  Route::get('/package_booking/complete', 'FrontEnd\Package\PackageBookingController@complete')->name('package_booking.complete');

  Route::get('/package_booking/cancel', 'FrontEnd\Package\PackageBookingController@cancel')->name('package_booking.cancel');

  Route::get('/faqs', 'FrontEnd\FAQController@faqs')->name('faqs');

  Route::get('/contact', 'FrontEnd\ContactController@contact')->name('contact');
});

Route::prefix('vendors')->middleware('language')->group(function () {
  Route::get('/', 'FrontEnd\VendorController@index')->name('frontend.vendors');
  Route::get('/{username}', 'FrontEnd\VendorController@details')->name('frontend.vendor.details');
  Route::post('review', 'FrontEnd\VendorController@review')->name('vendor.review');
  Route::post('contact/message', 'FrontEnd\VendorController@contact')->name('vendor.contact.message');
});


Route::post('/contact/send_mail', 'FrontEnd\ContactController@sendMail')->name('contact.send_mail');

Route::middleware(['guest:web'])->group(function () {
  Route::get('/login/facebook/callback', 'FrontEnd\UserController@handleFacebookCallback');
  Route::get('/login/google/callback', 'FrontEnd\UserController@handleGoogleCallback');
});

Route::prefix('/user')->middleware(['guest:web'])->group(function () {
  Route::get('/login/facebook', 'FrontEnd\UserController@redirectToFacebook')->name('user.facebook_login');

  Route::get('/login/google', 'FrontEnd\UserController@redirectToGoogle')->name('user.google_login');

  // user redirect to login page route
  Route::get('/login', 'FrontEnd\UserController@login')->name('user.login')->middleware('language');

  // user login submit route
  Route::post('/login_submit', 'FrontEnd\UserController@loginSubmit')->name('user.login_submit');

  // user forget password route
  Route::get('/forget_password', 'FrontEnd\UserController@forgetPassword')->name('user.forget_password')->middleware('language');

  // send mail to user for forget password route
  Route::post('/mail_for_forget_password', 'FrontEnd\UserController@sendMail')->name('user.mail_for_forget_password');

  // reset password route
  Route::get('/reset_password/{code}', 'FrontEnd\UserController@resetPassword')->middleware('language');

  // user reset password submit route
  Route::post('/reset_password_submit', 'FrontEnd\UserController@resetPasswordSubmit')->name('user.reset_password_submit');

  // user redirect to signup page route
  Route::get('/signup', 'FrontEnd\UserController@signup')->name('user.signup')->middleware('language');

  // user signup submit route
  Route::post('/signup_submit', 'FrontEnd\UserController@signupSubmit')->name('user.signup_submit');

  // signup verify route
  Route::get('/signup_verify/{token}', 'FrontEnd\UserController@signupVerify');
});

Route::prefix('/user')->middleware(['auth:web', 'language', 'EmailStatus:user', 'Deactive:user'])->group(function () {
  // user redirect to dashboard route
  Route::get('/dashboard', 'FrontEnd\UserController@redirectToDashboard')->name('user.dashboard');

  // all room bookings of user route
  Route::get('/room_bookings', 'FrontEnd\UserController@roomBookings')->name('user.room_bookings');

  // room booking details route
  Route::get('/room_booking_details/{id}', 'FrontEnd\UserController@roomBookingDetails')->name('user.room_booking_details');

  // all package bookings of user route
  Route::get('/package_bookings', 'FrontEnd\UserController@packageBookings')->name('user.package_bookings');

  // package booking details route
  Route::get('/package_booking_details/{id}', 'FrontEnd\UserController@packageBookingDetails')->name('user.package_booking_details');

  Route::prefix('support-ticket')->group(function () {
    Route::get('/', 'FrontEnd\SupportTicketController@index')->name('user.support_tickert');
    Route::get('/create', 'FrontEnd\SupportTicketController@create')->name('user.support_tickert.create');
    Route::post('store', 'FrontEnd\SupportTicketController@store')->name('user.support_ticket.store');
    Route::get('message/{id}', 'FrontEnd\SupportTicketController@message')->name('user.support_ticket.message');
    Route::post('reply/{id}', 'FrontEnd\SupportTicketController@reply')->name('user.support_ticket.reply');
  });


  // edit profile route
  Route::get('/edit_profile', 'FrontEnd\UserController@editProfile')->name('user.edit_profile');

  // update profile route
  Route::post('/update_profile', 'FrontEnd\UserController@updateProfile')->name('user.update_profile')->withoutMiddleware('language');

  // change password route
  Route::get('/change_password', 'FrontEnd\UserController@changePassword')->name('user.change_password');

  // update password route
  Route::post('/update_password', 'FrontEnd\UserController@updatePassword')->name('user.update_password')->withoutMiddleware('language');

  // user logout attempt route
  Route::get('/logout', 'FrontEnd\UserController@logoutSubmit')->name('user.logout')->withoutMiddleware('language');
});

/*
|--------------------------------------------------------------------------
| BackEnd Routes
|--------------------------------------------------------------------------
*/


Route::prefix('/admin')->middleware('guest:admin')->group(function () {

  // admin redirect to login page route
  Route::get('/', 'BackEnd\AdminController@login')->name('admin.login');

  // admin login attempt route
  Route::post('/auth', 'BackEnd\AdminController@authentication')->name('admin.auth');

  // admin forget password route
  Route::get('/forget_password', 'BackEnd\AdminController@forgetPassword')->name('admin.forget_password');

  // send mail to admin for forget password route
  Route::post('/mail_for_forget_password', 'BackEnd\AdminController@sendMail')->name('admin.mail_for_forget_password');
});



Route::get('/{slug}', 'App\Http\Controllers\FrontEnd\PageController@dynamicPage')->name('front.dynamicPage')->middleware('language');
