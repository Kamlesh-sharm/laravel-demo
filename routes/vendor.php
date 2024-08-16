<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/


Route::prefix('vendor')->middleware('language')->group(function () {
  Route::get('/dashboard', 'Vendor\VendorController@index')->name('vendor.index');
  Route::get('/signup', 'Vendor\VendorController@signup')->name('vendor.signup');
  Route::post('/signup/submit', 'Vendor\VendorController@create')->name('vendor.signup_submit');
  Route::get('/login', 'Vendor\VendorController@login')->name('vendor.login');
  Route::post('/login/submit', 'Vendor\VendorController@authentication')->name('vendor.login_submit');

  Route::get('/email/verify', 'Vendor\VendorController@confirm_email');

  Route::get('/forget-password', 'Vendor\VendorController@forget_passord')->name('vendor.forget.password');
  Route::post('/send-forget-mail', 'Vendor\VendorController@forget_mail')->name('vendor.forget.mail');
  Route::get('/reset-password', 'Vendor\VendorController@reset_password')->name('vendor.reset.password');
  Route::post('/update-forget-password', 'Vendor\VendorController@update_password')->name('vendor.update-forget-password');
});


Route::prefix('vendor')->middleware('auth:vendor', 'EmailStatus:vendor', 'Deactive:vendor',)->group(function () {
  Route::get('dashboard', 'Vendor\VendorController@dashboard')->name('vendor.dashboard');
  Route::get('monthly-income', 'Vendor\VendorController@monthly_income')->name('vendor.monthly_income');
  Route::get('/change-password', 'Vendor\VendorController@change_password')->name('vendor.change_password');
  Route::post('/update-password', 'Vendor\VendorController@updated_password')->name('vendor.update_password');
  Route::get('/edit-profile', 'Vendor\VendorController@edit_profile')->name('vendor.edit.profile');
  Route::post('/profile/update', 'Vendor\VendorController@update_profile')->name('vendor.update_profile');
  Route::get('/logout', 'Vendor\VendorController@logout')->name('vendor.logout');

  // change vendor-panel theme (dark/light) route
  Route::post('/change-theme', 'Vendor\VendorController@changeTheme')->name('vendor.change_theme');


  // rooms management route start
  Route::prefix('rooms_management')->group(function () {
    Route::get('/rooms', 'Vendor\RoomController@rooms')->name('vendor.rooms_management.rooms');
    Route::get('/create_room', 'Vendor\RoomController@createRoom')->name('vendor.rooms_management.create_room');
    Route::post('/store_room', 'Vendor\RoomController@storeRoom')->name('vendor.rooms_management.store_room');


    //sliders images
    Route::post('/rooms_management/images-store', 'Vendor\RoomController@gallerystore')->name('vendor.rooms_management.imagesstore');
    Route::post('rooms_management/room-imagermv', 'Vendor\RoomController@imagermv')->name('vendor.rooms_management.imagermv');

    Route::post('rooms_management/room-img-dbrmv', 'Vendor\RoomController@imagedbrmv')->name('vendor.rooms_management.imgdbrmv');
    Route::get('rooms_management/room-images/{id}', 'Vendor\RoomController@images')->name('vendor.rooms_management.images');
    //sliders images end

    Route::get('/edit_room/{id}', 'Vendor\RoomController@editRoom')->name('vendor.rooms_management.edit_room');
    Route::get('/slider_images/{id}', 'Vendor\RoomController@getSliderImages');
    Route::post('/update_room/{id}', 'Vendor\RoomController@updateRoom')->name('vendor.rooms_management.update_room');
    Route::post('/delete_room', 'Vendor\RoomController@deleteRoom')->name('vendor.rooms_management.delete_room');
    Route::post('/bulk_delete_room', 'Vendor\RoomController@bulkDeleteRoom')->name('vendor.rooms_management.bulk_delete_room');
  });
  // rooms management route end

  // Room Bookings Routes
  Route::prefix('room_bookings')->group(function () {
    Route::get('/all_bookings', 'Vendor\RoomBookingController@bookings')->name('vendor.room_bookings.all_bookings');

    Route::get('/paid_bookings', 'Vendor\RoomBookingController@bookings')->name('vendor.room_bookings.paid_bookings');

    Route::get('/unpaid_bookings', 'Vendor\RoomBookingController@bookings')->name('vendor.room_bookings.unpaid_bookings');

    Route::post('/update_payment_status', 'Vendor\RoomBookingController@updatePaymentStatus')->name('vendor.room_bookings.update_payment_status');

    Route::get('/booking_details_and_edit/{id}', 'Vendor\RoomBookingController@editBookingDetails')->name('vendor.room_bookings.booking_details_and_edit');

    Route::post('/update_booking', 'Vendor\RoomBookingController@updateBooking')->name('vendor.room_bookings.update_booking');

    Route::post('/send_mail', 'Vendor\RoomBookingController@sendMail')->name('vendor.room_bookings.send_mail');

    Route::post('/delete_booking/{id}', 'Vendor\RoomBookingController@deleteBooking')->name('vendor.room_bookings.delete_booking');

    Route::post('/bulk_delete_booking', 'Vendor\RoomBookingController@bulkDeleteBooking')->name('vendor.room_bookings.bulk_delete_booking');

    Route::get('/get_booked_dates', 'Vendor\RoomBookingController@bookedDates')->name('vendor.room_bookings.get_booked_dates');

    Route::get('/booking_form', 'Vendor\RoomBookingController@bookingForm')->name('vendor.room_bookings.booking_form');

    Route::post('/make_booking', 'Vendor\RoomBookingController@makeBooking')->name('vendor.room_bookings.make_booking');
  });


  // packages management route start
  Route::prefix('package-management')->group(function () {

    Route::get('/packages', 'Vendor\PackageController@packages')->name('vendor.packages_management.packages');

    Route::get('/create_package', 'Vendor\PackageController@createPackage')->name('vendor.packages_management.create_package');

    Route::post('/store_package', 'Vendor\PackageController@storePackage')->name('vendor.packages_management.store_package');

    //sliders images
    Route::post('/images-store', 'Vendor\PackageController@gallerystore')->name('vendor.packages_management.imagesstore');
    Route::post('room-imagermv', 'Vendor\PackageController@imagermv')->name('vendor.packages_management.imagermv');

    Route::post('room-img-dbrmv', 'Vendor\PackageController@imagedbrmv')->name('vendor.packages_management.imgdbrmv');
    Route::get('room-images/{id}', 'Vendor\PackageController@images')->name('vendor.packages_management.images');
    //sliders images end

    Route::get('/edit_package/{id}', 'Vendor\PackageController@editPackage')->name('vendor.packages_management.edit_package');

    Route::get('/slider_images/{id}', 'Vendor\PackageController@getSliderImages');

    Route::post('/update_package/{id}', 'Vendor\PackageController@updatePackage')->name('vendor.packages_management.update_package');

    Route::post('/delete_package', 'Vendor\PackageController@deletePackage')->name('vendor.packages_management.delete_package');

    Route::post('/bulk_delete_package', 'Vendor\PackageController@bulkDeletePackage')->name('vendor.packages_management.bulk_delete_package');

    Route::post('/store_location', 'Vendor\PackageController@storeLocation')->name('vendor.packages_management.store_location');

    Route::get('/view_locations/{package_id}', 'Vendor\PackageController@viewLocations')->name('vendor.packages_management.view_locations');

    Route::post('/update_location', 'Vendor\PackageController@updateLocation')->name('vendor.packages_management.update_location');

    Route::post('/delete_location', 'Vendor\PackageController@deleteLocation')->name('vendor.packages_management.delete_location');

    Route::post('/bulk_delete_location', 'Vendor\PackageController@bulkDeleteLocation')->name('vendor.packages_management.bulk_delete_location');

    Route::post('/store_daywise_plan', 'Vendor\PackageController@storeDaywisePlan')->name('vendor.packages_management.store_daywise_plan');

    Route::post('/store_timewise_plan', 'Vendor\PackageController@storeTimewisePlan')->name('vendor.packages_management.store_timewise_plan');

    Route::get('/view_plans/{package_id}', 'Vendor\PackageController@viewPlans')->name('vendor.packages_management.view_plans');

    Route::post('/update_daywise_plan', 'Vendor\PackageController@updateDaywisePlan')->name('vendor.packages_management.update_daywise_plan');

    Route::post('/update_timewise_plan', 'Vendor\PackageController@updateTimewisePlan')->name('vendor.packages_management.update_timewise_plan');

    Route::post('/delete_plan', 'Vendor\PackageController@deletePlan')->name('vendor.packages_management.delete_plan');

    Route::post('/bulk_delete_plan', 'Vendor\PackageController@bulkDeletePlan')->name('vendor.packages_management.bulk_delete_plan');
  });
  // packages management route end

  // Package Bookings Routes
  Route::prefix('package_bookings')->group(function () {
    Route::get('/all_bookings', 'Vendor\PackageBookingController@bookings')->name('vendor.package_bookings.all_bookings');

    Route::get('/paid_bookings', 'Vendor\PackageBookingController@bookings')->name('vendor.package_bookings.paid_bookings');

    Route::get('/unpaid_bookings', 'Vendor\PackageBookingController@bookings')->name('vendor.package_bookings.unpaid_bookings');

    Route::post('/update_payment_status', 'Vendor\PackageBookingController@updatePaymentStatus')->name('vendor.package_bookings.update_payment_status');

    Route::get('/booking_details/{id}', 'Vendor\PackageBookingController@bookingDetails')->name('vendor.package_bookings.booking_details');

    Route::post('/send_mail', 'Vendor\PackageBookingController@sendMail')->name('vendor.package_bookings.send_mail');

    Route::post('/delete_booking/{id}', 'Vendor\PackageBookingController@deleteBooking')->name('vendor.package_bookings.delete_booking');

    Route::post('/bulk_delete_booking', 'Vendor\PackageBookingController@bulkDeleteBooking')->name('vendor.package_bookings.bulk_delete_booking');
  });


  Route::prefix('withdraw')->middleware('Deactive')->group(function () {
    Route::get('/', 'Vendor\VendorWithdrawController@index')->name('vendor.withdraw');
    Route::get('/create', 'Vendor\VendorWithdrawController@create')->name('vendor.withdraw.create');
    Route::get('/get-method/input/{id}', 'Vendor\VendorWithdrawController@get_inputs');

    Route::get('/balance-calculation/{method}/{amount}', 'Vendor\VendorWithdrawController@balance_calculation');

    Route::post('/send-request', 'Vendor\VendorWithdrawController@send_request')->name('vendor.withdraw.send-request');
    Route::post('/witdraw/bulk-delete', 'Vendor\VendorWithdrawController@bulkDelete')->name('vendor.witdraw.bulk_delete_withdraw');
    Route::post('/witdraw/delete', 'Vendor\VendorWithdrawController@Delete')->name('vendor.witdraw.delete_withdraw');
  });

  Route::get('/transcation', 'Vendor\VendorController@transcation')->name('vendor.transcation');




  #====support tickets ============
  Route::prefix('support/ticket')->group(function () {
    Route::get('create', 'Vendor\SupportTicketController@create')->name('vendor.support_ticket.create');
    Route::post('store', 'Vendor\SupportTicketController@store')->name('vendor.support_ticket.store');
    Route::get('', 'Vendor\SupportTicketController@index')->name('vendor.support_tickets');

    Route::get('message/{id}', 'Vendor\SupportTicketController@message')->name('vendor.support_tickets.message');

    Route::post('zip-upload', 'Vendor\SupportTicketController@zip_file_upload')->name('vendor.support_ticket.zip_file.upload');

    Route::post('reply/{id}', 'Vendor\SupportTicketController@ticketreply')->name('vendor.support_ticket.reply');

    Route::post('delete/{id}', 'Vendor\SupportTicketController@delete')->name('vendor.support_tickets.delete');
    Route::post('bulk/delete/', 'Vendor\SupportTicketController@bulk_delete')->name('vendor.support_tickets.bulk_delete');
  });
});
