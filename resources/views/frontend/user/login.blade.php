@extends('frontend.layout')

@section('pageHeading')
  {{ __('Login') }}
@endsection

@php
  $metaKeys = !empty($seo->meta_keyword_login) ? $seo->meta_keyword_login : '';
  $metaDesc = !empty($seo->meta_description_login) ? $seo->meta_description_login : '';
@endphp

@section('meta-keywords', "$metaKeys")
@section('meta-description', "$metaDesc")

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center lazy"
      data-bg="{{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }}">
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ __('Login') }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Login') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Login Area Start -->
    <div class="user-area-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            @if ($basicInfo->facebook_login_status == 1 || $basicInfo->google_login_status == 1)
              <div class="social-logins my-4">
                <div class="btn-group btn-group-toggle d-flex">
                  @if ($basicInfo->facebook_login_status == 1)
                    <a class="btn py-2 facebook-login-btn" href="{{ route('user.facebook_login') }}">
                      <i class="fab fa-facebook-f {{ $currentLanguageInfo->direction == 0 ? 'mr-2' : 'ml-2' }}"></i>
                      {{ __('Login via Facebook') }}
                    </a>
                  @endif

                  @if ($basicInfo->google_login_status == 1)
                    <a class="btn py-2 google-login-btn" href="{{ route('user.google_login') }}">
                      <i class="fab fa-google {{ $currentLanguageInfo->direction == 0 ? 'mr-2' : 'ml-2' }}"></i>
                      {{ __('Login via Google') }}
                    </a>
                  @endif
                </div>
              </div>
            @endif

            <div class="user-content">
              <form action="{{ route('user.login_submit') }}" method="POST">
                @csrf
                <div class="input-box">
                  <label>{{ __('Username') . '*' }}</label>
                  <input type="text" name="username" value="{{ old('username') }}">
                  @error('username')
                    <p class="mt-3 ml-2 text-danger">{{ $message }}</p>
                  @enderror
                </div>

                <div class="input-box">
                  <label>{{ __('Password') . '*' }}</label>
                  <input type="password" name="password" value="{{ old('password') }}">
                  @error('password')
                    <p class="mt-3 ml-2 text-danger">{{ $message }}</p>
                  @enderror
                </div>

                @if ($websiteInfo->google_recaptcha_status == 1)
                  <div class="d-block mb-4">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                      @php
                        $errmsg = $errors->first('g-recaptcha-response');
                      @endphp
                      <p class="text-danger mb-0 mt-2">{{ __("$errmsg") }}</p>
                    @endif
                  </div>
                @endif

                <div class="input-box">
                  <button type="submit" class="btn">{{ __('Log In') }}</button>
                  <a href="{{ route('user.forget_password') }}">{{ __('Lost your password?') }}</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Login Area End -->
  </main>
@endsection
