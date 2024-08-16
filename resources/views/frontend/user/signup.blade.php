@extends('frontend.layout')

@section('pageHeading')
  {{ __('Signup') }}
@endsection

@php
    $metaKeys = !empty($seo->meta_keyword_registration) ? $seo->meta_keyword_registration : '';
    $metaDesc = !empty($seo->meta_description_registration) ? $seo->meta_description_registration : '';
@endphp

@section('meta-keywords', "$metaKeys")
@section('meta-description', "$metaDesc")

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center lazy" data-bg="{{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }}" >
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ __('Signup') }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Signup') }}</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Signup Area Start -->
    <div class="user-area-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="user-content">
              <form action="{{ route('user.signup_submit') }}" method="POST">
                @csrf
                <div class="input-box">
                  <label>{{ __('Username') . '*' }}</label>
                  <input type="text" name="username" value="{{ old('username') }}">
                  @error('username')
                    <p class="mt-3 ml-2 text-danger">{{ $message }}</p>
                  @enderror
                </div>

                <div class="input-box">
                  <label>{{ __('Email Address') . '*' }}</label>
                  <input type="email" name="email" value="{{ old('email') }}">
                  @error('email')
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

                <div class="input-box">
                  <label>{{ __('Confirm Password') . '*' }}</label>
                  <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                  @error('password_confirmation')
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
                        <p class="text-danger mb-0 mt-2">{{__("$errmsg")}}</p>
                        @endif
                    </div>
                @endif

                <div class="input-box">
                  <button type="submit" class="btn">{{ __('sign up') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Signup Area End -->
  </main>
@endsection
