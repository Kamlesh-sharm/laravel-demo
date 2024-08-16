@extends('frontend.layout')

@section('pageHeading')
  {{ __('Home') }}
@endsection

@php
  $metaKeywords = !empty($seo->meta_keyword_home) ? $seo->meta_keyword_home : '';
  $metaDescription = !empty($seo->meta_description_home) ? $seo->meta_description_home : '';
@endphp
@section('meta-keywords', "{{ $metaKeywords }}")
@section('meta-description', "$metaDescription")

@section('content')
  <main>
    @php
      if (!empty($hero)) {
          $img = $hero->img;
          $title = $hero->title;
          $subtitle = $hero->subtitle;
          $btnUrl = $hero->btn_url;
          $btnName = $hero->btn_name;
      } else {
          $img = '';
          $title = '';
          $subtitle = '';
          $btnUrl = '';
          $btnName = '';
      }
    @endphp
    @if ($websiteInfo->home_version == 'static')
      @includeIf('frontend.partials.hero.theme1.static')
    @elseif ($websiteInfo->home_version == 'slider')
      @includeIf('frontend.partials.hero.theme1.slider')
    @elseif ($websiteInfo->home_version == 'video')
      @includeIf('frontend.partials.hero.theme1.video')
    @elseif ($websiteInfo->home_version == 'particles')
      @includeIf('frontend.partials.hero.theme1.particles')
    @elseif ($websiteInfo->home_version == 'water')
      @includeIf('frontend.partials.hero.theme1.water')
    @elseif ($websiteInfo->home_version == 'parallax')
      @includeIf('frontend.partials.hero.theme1.parallax')
    @endif

    @if ($sections->search_section == 1)
      <!-- Booking Search Form Start -->
      <section class="booking-section">
        <div class="container">
          <div class="booking-form-wrap bg-img-center section-bg">
            <form action="{{ route('rooms') }}" method="GET">
              <div class="row no-gutters">
                <div class="col-lg-3 col-md-6">
                  <div class="input-wrap">
                    <input type="text" placeholder="{{ __('Check In / Out Date') }}" id="date-range" name="dates"
                      readonly>
                    <i class="far fa-calendar-alt"></i>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6">
                  <div class="input-wrap">
                    <input type="text" placeholder="{{ __('Location') }}" name="location">
                    <i class="fas fa-map-marker"></i>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6">
                  <div class="input-wrap">
                    <select name="beds" class="nice-select">
                      <option selected disabled>{{ __('Beds') }}</option>

                      @for ($i = 1; $i <= $numOfBed; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                      @endfor
                    </select>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6">
                  <div class="input-wrap">
                    <button type="submit" class="btn filled-btn btn-block rounded-0">
                      {{ __('search') }} <i class="far fa-long-arrow-right"></i>
                    </button>
                  </div>
                </div>
              </div>
            </form>

            <div class="booking-shape-1">
              <img class="lazy" data-src="{{ asset('assets/img/shape/01.png') }}" alt="shape">
            </div>
            <div class="booking-shape-2">
              <img class="lazy" data-src="{{ asset('assets/img/shape/02.png') }}" alt="shape">
            </div>
            <div class="booking-shape-3">
              <img class="lazy" data-src="{{ asset('assets/img/shape/03.png') }}" alt="shape">
            </div>
          </div>
        </div>
      </section>
      <!-- Booking Search Form End -->
    @endif

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


    @if ($sections->featured_rooms_section == 1)
      <!-- Latest Room Section Start -->
      <section class="latest-room section-bg section-padding">
        <div class="container-fluid">
          <div class="row align-items-center no-gutters">
            <div class="col-lg-3">
              <!-- Section Title -->
              <div class="section-title">
                @if (!is_null($secHeading))
                  <span class="title-top with-border">{{ convertUtf8($secHeading->room_section_title) }}</span>
                  <h1>{{ convertUtf8($secHeading->room_section_subtitle) }}</h1>
                  <p>{{ $secHeading->room_section_text }}</p>
                @endif
                <!-- Page Info -->
                <div class="page-Info"></div>
                <!-- Room Arrow -->
                <div class="room-arrows"></div>
              </div>
            </div>

            <div class="col-lg-8 offset-lg-1">
              @if (count($roomInfos) == 0 || $roomFlag == 0)
                <h3 class="text-center text-white">{{ __('No Featured Room Found!') }}</h3>
              @else
                <div class="latest-room-slider" id="roomSliderActive">
                  @foreach ($roomInfos as $roomInfo)
                    @if (!is_null($roomInfo->room))
                      <div class="single-room">
                        <a class="room-thumb d-block"
                          href="{{ route('room_details', [$roomInfo->room_id, $roomInfo->slug]) }}">
                          <img class="lazy" data-src="{{ asset('assets/img/rooms/' . $roomInfo->room->featured_img) }}"
                            alt="">
                          <div class="room-price">
                            <p>
                              {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                              {{ $roomInfo->room->rent }}
                              {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                              / {{ __('Night') }}</p>
                          </div>
                        </a>
                        <div class="room-desc">
                          @if ($websiteInfo->room_category_status == 1)
                            <div class="room-cat">
                              <a class="d-block p-0"
                                href="{{ route('rooms', ['category' => $roomInfo->roomCategory->id]) }}">{{ $roomInfo->roomCategory->name }}</a>
                            </div>
                          @endif
                          <h4>
                            <a
                              href="{{ route('room_details', ['id' => $roomInfo->room_id, 'slug' => $roomInfo->slug]) }}">{{ convertUtf8($roomInfo->title) }}</a>
                          </h4>
                          <h6>
                            @if ($roomInfo->room->vendor_id != null)
                              @php
                                $vendor = App\Models\Vendor::where('id', $roomInfo->room->vendor_id)->first();
                              @endphp
                              <a href="{{ route('frontend.vendor.details', $vendor->username) }}">{{ __('By') }}
                                {{ $vendor->username }}</a>
                            @else
                              @php
                                $admin = App\Models\Admin::first();
                              @endphp
                              <a href="{{ route('frontend.vendor.details', [$admin->username, 'admin' => 'true']) }}">{{ __('By') }}

                                {{ $admin->username }}</a>
                            @endif

                          </h6>

                          <p>{{ $roomInfo->summary }}</p>
                          <ul class="room-info">
                            <li><i class="far fa-bed"></i>{{ $roomInfo->room->bed }}
                              {{ $roomInfo->room->bed == 1 ? __('Bed') : __('Beds') }}</li>
                            <li><i class="far fa-bath"></i>{{ $roomInfo->room->bath }}
                              {{ $roomInfo->room->bath == 1 ? __('Bath') : __('Baths') }}</li>
                            @if (!empty($roomInfo->room->max_guests))
                              <li><i class="far fa-users"></i>{{ $roomInfo->room->max_guests }}
                                {{ $roomInfo->room->max_guests == 1 ? __('Guest') : __('Guests') }}</li>
                            @endif
                          </ul>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>
      </section>
      <!-- Latest Room Section End -->
    @endif

    @if ($sections->featured_services_section == 1)
      <!-- Service Section Start -->
      <section class="service-section section-padding">
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

          <!-- Service Boxes -->
          @if (count($serviceInfos) == 0 || $serviceFlag == 0)
            <div class="row text-center">
              <div class="col">
                <h3>{{ __('No Featured Service Found!') }}</h3>
              </div>
            </div>
          @else
            <div class="row">
              @foreach ($serviceInfos as $serviceInfo)
                @if (!is_null($serviceInfo->service))
                  <div class="col-lg-4 col-md-6">
                    <div class="single-service-box text-center wow fadeIn animated" data-wow-duration="1500ms"
                      data-wow-delay="{{ $loop->iteration * 200 }}ms">
                      <span class="service-counter">{{ $loop->iteration }}</span>
                      <div class="service-icon">
                        <i class="{{ $serviceInfo->service->service_icon }}"></i>
                      </div>
                      <h4>{{ convertUtf8($serviceInfo->title) }}</h4>
                      <p>
                        {{ strlen($serviceInfo->summary) > 35 ? substr($serviceInfo->summary, 0, 35) . '...' : $serviceInfo->summary }}
                      </p>
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
      <!-- Service Section End -->
    @endif


    @if ($sections->video_section == 1)
      <!-- Call To Action Start -->
      <section class="cta-section bg-img-center lazy {{ $websiteInfo->home_version == 'parallax' ? 'parallax' : '' }}"
        data-bg="{{ asset('assets/img/booking-img.jpg') }}">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-10">
              <div class="cta-left-content">
                @if (!is_null($secHeading))
                  <span>{{ convertUtf8($secHeading->booking_section_title) }}</span>
                  <h1>{{ convertUtf8($secHeading->booking_section_subtitle) }}</h1>
                  <a href="{{ $secHeading->booking_section_button_url }}" class="btn filled-btn">
                    {{ $secHeading->booking_section_button }} <i class="far fa-long-arrow-right"></i>
                  </a>
                @endif
              </div>
            </div>

            <div class="col-md-2">
              @if (!is_null($secHeading))
                <div class="video-icon text-right">
                  <a href="{{ $secHeading->booking_section_video_url }}" class="video-popup"> <i
                      class="fas fa-play"></i></a>
                </div>
              @endif
            </div>
          </div>
        </div>
      </section>
      <!-- Call To Action End -->
    @endif

    @if ($sections->featured_package_section == 1)
      <!-- Package Section Start -->
      <section class="ma-package-section section-padding featured-packages">
        <div class="container">
          <!-- Section Title -->
          <div class="section-title text-center">
            @if (!empty($secHeading))
              <div class="row justify-content-center">
                <div class="col-lg-7">
                  <span class="title-top">{{ convertUtf8($secHeading->package_section_title) }}</span>
                  <h1>{{ convertUtf8($secHeading->package_section_subtitle) }}</h1>
                </div>
              </div>
            @endif
          </div>

          <!-- Package Boxes -->
          @if (count($packageInfos) == 0 || $packageFlag == 0)
            <div class="row text-center">
              <div class="col">
                <h3>{{ __('No Featured Package Found!') }}</h3>
              </div>
            </div>
          @else
            <div class="row">
              @foreach ($packageInfos as $packageInfo)
                @if (!is_null($packageInfo->package))
                  <div class="col-lg-6">
                    <div class="packages-post-item">
                      <a class="post-thumbnail d-block"
                        href="{{ route('package_details', ['id' => $packageInfo->package_id, 'slug' => $packageInfo->slug]) }}">
                        <img class="lazy"
                          data-src="{{ asset('assets/img/package/' . $packageInfo->package->featured_img) }}"
                          alt="package img">
                      </a>

                      <div class="entry-content">
                        <h3 class="title">
                          <a
                            href="{{ route('package_details', ['id' => $packageInfo->package_id, 'slug' => $packageInfo->slug]) }}">{{ strlen($packageInfo->title) > 50 ? mb_substr($packageInfo->title, 0, 50, 'utf-8') . '...' : $packageInfo->title }}</a>
                        </h3>
                        <h6>
                          @if ($packageInfo->package->vendor_id != null)
                            @php
                              $vendor = App\Models\Vendor::where('id', $packageInfo->package->vendor_id)->first();
                            @endphp
                            <a href="{{ route('frontend.vendor.details', $vendor->username) }}">{{ __('By') }}
                              {{ $vendor->username }}</a>
                          @else
                            @php
                              $admin = App\Models\Admin::first();
                            @endphp
                            <a href="{{ route('frontend.vendor.details', [$admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                              {{ $admin->username }}</a>
                          @endif
                        </h6>
                        <div class="post-meta">
                          <ul>

                            @if ($packageInfo->package->pricing_type != 'negotiable')
                              <li><span><i
                                    class="fas fa-comment-dollar"></i><strong>{{ __('Package Price') . ':' }}</strong>
                                  {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                  {{ $packageInfo->package->package_price }}
                                  {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                  {{ '(' . __(strtoupper($packageInfo->package->pricing_type)) . ')' }}</span></li>
                            @else
                              <li><span><i
                                    class="fas fa-comment-dollar"></i><strong>{{ __('Package Price') . ':' }}</strong>
                                  {{ __('NEGOTIABLE') }}</span></li>
                            @endif

                            <li><span><i class="fas fa-users"></i><strong>{{ __('Number of Days') . ':' }}</strong>
                                {{ $packageInfo->package->number_of_days }}</span></li>

                            <li><span><i class="fas fa-users"></i><strong>{{ __('Maximum Persons') . ':' }}</strong>
                                {{ $packageInfo->package->max_persons != null ? $packageInfo->package->max_persons : '-' }}</span>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          @endif
        </div>
      </section>
      <!-- Package Section End -->
    @endif

    @if ($sections->facilities_section == 1)
      <!-- Why Choose Us/Facility Section Start -->
      <section class="wcu-section section-bg section-padding">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-5 offset-lg-1">
              <!-- Section Title -->
              <div class="feature-left">
                <div class="section-title">
                  @if (!is_null($secHeading))
                    <span class="title-top with-border">{{ convertUtf8($secHeading->facility_section_title) }}</span>
                    <h1>{{ convertUtf8($secHeading->facility_section_subtitle) }}</h1>
                  @endif
                </div>

                @if (count($facilities) > 0)
                  <ul class="feature-list">
                    @foreach ($facilities as $facility)
                      <li class="wow fadeInUp animated" data-wow-duration="1000ms"
                        data-wow-delay="{{ $loop->iteration * 100 }}ms">
                        <div class="feature-icon"><i class="{{ $facility->facility_icon }}"></i></div>
                        <h4>{{ convertUtf8($facility->facility_title) }}</h4>
                        <p>{{ $facility->facility_text }}</p>
                      </li>
                    @endforeach
                  </ul>
                @endif
              </div>
            </div>

            <div class="col-lg-6">
              @if (!is_null($secHeading))
                <div class="feature-img">
                  <div class="feature-abs-con">
                    <div class="f-inner">
                      <i class="far fa-stars"></i>
                      <p>{{ __('Popular Features') }}</p>
                    </div>
                  </div>
                  <img class="lazy"
                    data-src="{{ asset('assets/img/facility_section/' . $secHeading->facility_section_image) }}"
                    alt="image">
                </div>
              @endif
            </div>
          </div>
        </div>
      </section>
      <!-- Why Choose Us/Facility Section End -->
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
                  <img class="lazy" data-src="{{ asset('assets/img/brands/' . $brand->brand_img) }}"
                    alt="brand image">
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

@section('script')
  <script src="{{ asset('assets/js/home.js') }}"></script>
@endsection
