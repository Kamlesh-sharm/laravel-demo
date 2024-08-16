@extends('frontend.layout')

@section('pageHeading')
  @if (!is_null($pageHeading))
    {{ $pageHeading->about_us_title }}
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
            <h1>{{ convertUtf8($pageHeading->about_us_title) }}</h1>
          @endif
          

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            @if (!is_null($pageHeading))
              <li>{{ convertUtf8($pageHeading->about_us_title) }}</li>
            @endif
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- About Section Start -->
    @if ($sections->intro_section == 1)
      <!-- Welcome Section Start -->
      <section class="welcome-section section-padding">
        <div class="container">
          <div class="row align-items-center no-gutters">
            <!-- Title Gallery Start -->
            <div class="col-lg-6">
              <div class="title-gallery">
                @if (!is_null($intro))
                  <img class="lazy" data-src="{{ asset('assets/img/intro_section/' . $intro->intro_img) }}"
                    alt="image">
                @endif
              </div>
            </div>
            <!-- Title Gallery End -->

            <div class="col-lg-5 offset-lg-1">
              <!-- Section Title -->
              <div class="section-title">
                @if (!is_null($intro))
                  <span class="title-top with-border">{{ $intro->intro_primary_title }}</span>
                  <h1>{{ $intro->intro_secondary_title }}</h1>
                  <p>{{ $intro->intro_text }}</p>
                @endif
              </div>

              @if ($sections->statistics_section == 1)
                <!-- Counter Start -->
                <div class="counter">
                  <div class="row">
                    @if (count($counterInfos) > 0)
                      @foreach ($counterInfos as $counterInfo)
                        <div class="col-sm-4">
                          <div class="counter-box">
                            <i class="{{ $counterInfo->icon }}"></i>
                            <span class="counter-number">{{ $counterInfo->amount }}</span>
                            <p>{{ $counterInfo->title }}</p>
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>
                </div>
                <!-- Counter End -->
              @endif
            </div>
          </div>
        </div>
      </section>
      <!-- Welcome Section End -->
    @endif
    <!-- About Section End -->

    @if ($sections->featured_services_section == 1)
      <!-- Feature Service Section Start -->
      <section class="feature-section section-padding pt-0">
        <div class="container">
          <!-- Section Title -->
          <div class="section-title text-center">
            @if (!empty($secHeading))
              <div class="row justify-content-center">
                <div class="col-lg-7">
                  <span class="title-top">{{ convertUtf8($secHeading->service_section_title) }}</span>
                  <h1>{{ convertUtf8($secHeading->service_section_subtitle) }}</h1>
                </div>
              </div>
            @endif
          </div>

          <!-- Single Service Box -->
          @if (count($serviceInfos) == 0 || $serviceFlag == 0)
            <div class="row text-center">
              <div class="col">
                <h3>{{ __('No Featured Service Found!') }}</h3>
              </div>
            </div>
          @else
            <div class="row">
              @foreach ($serviceInfos as $serviceInfo)
                @if (!empty($serviceInfo->service))
                  <div class="col-lg-4 col-md-6">
                    <div class="single-feature-box text-center wow fadeIn animated" data-wow-duration="1500ms"
                      data-wow-delay="400ms">
                      <div class="feature-icon">
                        <i class="{{ $serviceInfo->service->service_icon }}"></i>
                      </div>
                      <h4>{{ convertUtf8($serviceInfo->title) }}</h4>
                      <p>{{ $serviceInfo->summary }}</p>
                      @if ($serviceInfo->service->details_page_status == 1)
                        <a href="{{ route('service_details', ['id' => $serviceInfo->service_id, 'slug' => $serviceInfo->slug]) }}"
                          class="read-more">
                          {{ __('read more') }} <i class="far fa-long-arrow-right"></i>
                        </a>
                      @endif
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          @endif
        </div>
      </section>
      <!-- Feature Service Section Start -->
    @endif

    @if ($sections->testimonials_section == 1)
      <!-- Feedback Section Start -->
      <section class="feedback-section section-padding">
        <div class="container">
          <!-- Section Title -->
          <div class="section-title text-center">
            @if (!empty($secHeading))
              <div class="row justify-content-center">
                <div class="col-lg-7">
                  <span class="title-top">{{ convertUtf8($secHeading->testimonial_section_title) }}</span>
                  <h1>{{ convertUtf8($secHeading->testimonial_section_subtitle) }}</h1>
                </div>
              </div>
            @endif
          </div>

          @if (count($testimonials) == 0)
            <div class="row text-center">
              <div class="col">
                <h3 class="text-white">{{ __('No Testimonial Found!') }}</h3>
              </div>
            </div>
          @else
            <div class="feadback-slide" id="feedbackSlideActive">
              @foreach ($testimonials as $testimonial)
                <div class="single-feedback-box">
                  <p>{{ $testimonial->comment }}</p>
                  <h5 class="feedback-author">{{ convertUtf8($testimonial->client_name) }}</h5>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </section>
      <!-- Feedback Section End -->
    @endif

    @if ($sections->brand_section == 1)
      <!-- Brands Section Start -->
      <section class="brands-section primary-bg">
        <div class="container">
          @if (count($brands) == 0)
            <div class="row text-center">
              <div class="col">
                <h3>{{ __('No Brand Found!') }}</h3>
              </div>
            </div>
          @else
            <div id="brandsSlideActive" class="row">
              @foreach ($brands as $brand)
                <a class="brand-item text-center d-block" href="{{ $brand->brand_url }}" target="_blank">
                  <img class="lazy" data-src="{{ asset('assets/img/brands/' . $brand->brand_img) }}" alt="brand image">
                </a>
              @endforeach
            </div>
          @endif
        </div>
      </section>
      <!-- Brands Section End -->
    @endif

  </main>
@endsection
