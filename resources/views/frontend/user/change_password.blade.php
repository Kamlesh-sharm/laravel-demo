@extends('frontend.layout')

@section('pageHeading')
  {{ __('Change Password') }}
@endsection

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section
      class="breadcrumb-area d-flex align-items-center position-relative bg-img-center"
      style="background-image: url({{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }});"
    >
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ __('Change Password') }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Change Password') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Change Password Area Start -->
    <section class="user-dashboard">
      <div class="container">
        <div class="row">
          @include('frontend.user.side_navbar')

          <div class="col-lg-9">
            <div class="row mb-5">
              <div class="col-lg-12">
                <div class="user-profile-details">
                  <div class="account-info">
                    <div class="title">
                      <h4>{{ __('Change Password') }}</h4>
                    </div>

                    <div class="edit-info-area">
                      <form action="{{ route('user.update_password') }}" method="POST">
                        @csrf
                        <div class="row">
                          <div class="col-lg-12">
                            <input type="password" class="form_control" placeholder="{{ __('Current Password') }}" name="current_password">

                            @error('current_password')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-12">
                            <input type="password" class="form_control" placeholder="{{ __('New Password') }}" name="new_password">

                            @error('new_password')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-12">
                            <input type="password" class="form_control" placeholder="{{ __('Confirm New Password') }}" name="new_password_confirmation">

                            @error('new_password_confirmation')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-button">
                              <button class="btn filled-btn">{{ __('Submit') }}</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Change Password Area End -->
  </main>
@endsection
