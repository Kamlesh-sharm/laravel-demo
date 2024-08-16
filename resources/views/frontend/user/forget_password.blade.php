@extends('frontend.layout')

@section('pageHeading')
  {{ __('Forget Password') }}
@endsection

@php
    $metaKeys = !empty($seo->meta_keyword_forget_password) ? $seo->meta_keyword_forget_password : '';
    $metaDesc = !empty($seo->meta_description_forget_password) ? $seo->meta_description_forget_password : '';
@endphp

@section('meta-keywords', "$metaKeys")
@section('meta-description', "$metaDesc")

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section
      class="breadcrumb-area d-flex align-items-center position-relative bg-img-center"
      style="background-image: url({{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }});"
    >
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ __('Forget Password') }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Forget Password') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Forget Password Area Start -->
    <div class="user-area-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="user-content">
              <form action="{{ route('user.mail_for_forget_password') }}" method="POST">
                @csrf
                <div class="input-box">
                  <label>{{ __('Email Address') . '*' }}</label>
                  <input type="email" name="email" value="{{ old('email') }}">
                  @error('email')
                    <p class="mt-3 ml-2 text-danger">{{ $message }}</p>
                  @enderror
                </div>

                <div class="input-box">
                  <button type="submit" class="btn">{{ __('proceed') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Forget Password Area End -->
  </main>
@endsection
