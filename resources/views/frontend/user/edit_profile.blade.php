@extends('frontend.layout')

@section('pageHeading')
  {{ __('Edit Profile') }}
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
          <h1>{{ __('Edit Profile') }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Edit Profile') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Edit Profile Area Start -->
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
                      <h4>{{ __('Edit Profile') }}</h4>
                    </div>

                    <div class="edit-info-area">
                      <form action="{{ route('user.update_profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $userInfo->id }}">

                        <div class="upload-img">
                          <div class="img-box">
                            <img
                              src="{{ is_null($userInfo->image) ? asset('assets/img/user-profile.jpg') : asset('assets/img/users/' . $userInfo->image) }}"
                              alt="user image"
                              class="user-photo"
                            >
                          </div>

                          <div class="file-upload-area">
                            <div class="upload-file">
                              <input type="file" accept=".jpg, .jpeg, .png" name="user_image" class="upload">
                              <span>{{ __('Upload') }}</span>
                            </div>
                          </div>
                          @error('user_image')
                            <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                          @enderror
                        </div>

                        <div class="row">
                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('First Name') }}" name="first_name" value="{{ $userInfo->first_name }}">
                            @error('first_name')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('Last Name') }}" name="last_name" value="{{ $userInfo->last_name }}">
                            @error('last_name')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-6">
                            <input type="email" class="form_control" placeholder="{{ __('Email') }}" value="{{ $userInfo->email }}" readonly>
                          </div>

                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('Username') }}" name="username" value="{{ $userInfo->username }}">
                            @error('username')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('Contact Number') }}" name="contact_number" value="{{ $userInfo->contact_number }}">
                            @error('contact_number')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-12">
                            <textarea class="form_control" placeholder="{{ __('Address') }}" name="address">{{ $userInfo->address }}</textarea>
                            @error('address')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('City') }}" name="city" value="{{ $userInfo->city }}">
                            @error('city')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-6">
                            <input type="text" class="form_control" placeholder="{{ __('State') }}" name="state" value="{{ $userInfo->state }}">
                            @error('state')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-12">
                            <input type="text" class="form_control" placeholder="{{ __('Country') }}" name="country" value="{{ $userInfo->country }}">
                            @error('country')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-12">
                            <div class="form-button">
                              <button class="btn form-btn">{{ __('Submit') }}</button>
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
    <!-- Edit Profile Area End -->
  </main>
@endsection

@section('script')
  <script src="{{asset('assets/js/admin-profile.js')}}"></script>
@endsection
