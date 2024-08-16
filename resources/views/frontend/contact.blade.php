@extends('frontend.layout')

@section('pageHeading')
  @if (!is_null($pageHeading))
    {{ $pageHeading->contact_us_title }}
  @endif
@endsection

@php
  $metaKeys = !empty($seo->meta_keyword_contact_us) ? $seo->meta_keyword_contact_us : '';
  $metaDesc = !empty($seo->meta_description_contact_us) ? $seo->meta_description_contact_us : '';
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
          @if (!is_null($pageHeading))
            <h1>{{ convertUtf8($pageHeading->contact_us_title) }}</h1>
          @endif

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            @if (!is_null($pageHeading))
              <li>{{ convertUtf8($pageHeading->contact_us_title) }}</li>
            @endif
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Contact Information Start -->
    <section class="contact-info-section">
      <div class="container">
        <div class="contact-info-boxes">
          <div class="row">
            <div class="col-lg-4 col-md-6">
              <div class="contact-info-box">
                <div class="contact-icon">
                  <i class="far fa-map-marker-alt"></i>
                </div>
                <h4>{{ __('Address') }}</h4>
                <p>{{ $websiteInfo->address }}</p>
              </div>
            </div>

            <div class="col-lg-4 col-md-6">
              <div class="contact-info-box">
                <div class="contact-icon">
                  <i class="far fa-envelope-open"></i>
                </div>
                <h4>{{ __('Email') }}</h4>
                <p>{{ $websiteInfo->support_email }}</p>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 mx-auto">
              <div class="contact-info-box">
                <div class="contact-icon">
                  <i class="far fa-phone"></i>
                </div>
                <h4>{{ __('Phone') }}</h4>
                <p>{{ $websiteInfo->support_contact }}</p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- Contact Information End -->

    <!-- Map Start -->
    @if (!empty($websiteInfo->latitude) && !empty($websiteInfo->longitude))
      <section class="contact-map">
        <iframe width="100%" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
          src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $websiteInfo->latitude }},%20{{ $websiteInfo->longitude }}+(My%20Business%20Name)&amp;t=&amp;z=10&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
      </section>
    @endif
    <!-- Map End -->

    <!-- Send Mail Form Start -->
    <section class="contact-form">
      <div class="container">
        <div class="contact-form-wrap section-bg">
          <h2 class="form-title">{{ __('Send A Message') }}</h2>
          <form action="{{ route('contact.send_mail') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-md-4 col-12">
                <div class="mb-4">
                  <div class="input-wrap mb-0">
                    <input type="text" placeholder="{{ __('Full Name') }}" name="full_name">
                    <i class="far fa-user-alt"></i>
                  </div>
                  @error('full_name')
                    <p class="mb-0 ml-3 text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-12">
                <div class="mb-4">
                  <div class="input-wrap mb-0">
                    <input type="email" placeholder="{{ __('Email Address') }}" name="email">
                    <i class="far fa-envelope"></i>
                  </div>
                  @error('email')
                    <p class="mb-0 ml-3 text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="col-md-4 col-12">
                <div class="mb-4">
                  <div class="input-wrap mb-0">
                    <input type="text" placeholder="{{ __('Email Subject') }}" name="subject">
                    <i class="far fa-pencil"></i>
                  </div>
                  @error('subject')
                    <p class="mb-0 ml-3 text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              <div class="col-12">
                <div class="mb-4">
                  <div class="input-wrap mb-0 text-area">
                    <textarea placeholder="{{ __('Write Message') }}" name="message"></textarea>
                    <i class="far fa-pencil"></i>
                  </div>
                  @error('message')
                    <p class="mb-0 ml-3 text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>

              @if ($websiteInfo->google_recaptcha_status == 1)
                <div class="col-12 text-center">
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
                </div>
              @endif

              <div class="col-12 text-center">
                <button type="submit" class="btn filled-btn">
                  {{ __('Send') }} <i class="far fa-long-arrow-right"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
    <!-- Send Mail Form End -->
  </main>
@endsection
