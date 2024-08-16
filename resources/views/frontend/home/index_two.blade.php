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
      @includeIf('frontend.partials.hero.theme2.static')
    @elseif ($websiteInfo->home_version == 'slider')
      @includeIf('frontend.partials.hero.theme2.slider')
    @elseif ($websiteInfo->home_version == 'video')
      @includeIf('frontend.partials.hero.theme2.video')
    @elseif ($websiteInfo->home_version == 'particles')
      @includeIf('frontend.partials.hero.theme2.particles')
    @elseif ($websiteInfo->home_version == 'water')
      @includeIf('frontend.partials.hero.theme2.water')
    @elseif ($websiteInfo->home_version == 'parallax')
      @includeIf('frontend.partials.hero.theme2.parallax')
    @endif

    @if ($sections->search_section == 1)
      <!-- Booking Search Form Start -->
      <section class="booking-section style-two primary-bg">
        <div class="container container-lg-fluid">
          <div class="row no-gutters justify-content-center">
            <div class="col-xl-10">
              <div class="booking-form-wrap">
                <form action="{{ route('rooms') }}" method="GET">
                  <div class="bookIng-inner-wrap">
                    <div class="row">
                      <div class="col-lg-3">
                        <div class="input-wrap">
                          <input type="text" placeholder="{{ __('Check In / Out Date') }}" id="date-range"
                            name="dates" readonly>
                          <i class="far fa-calendar-alt"></i>
                        </div>
                      </div>

                      <div class="col-lg-3">
                        <div class="input-wrap">
                          <input type="text" placeholder="{{ __('Location') }}" name="location">
                          <i class="fas fa-map-marker"></i>
                        </div>
                      </div>

                      <div class="col-lg-3">
                        <div class="input-wrap">
                          <select name="beds" class="nice-select">
                            <option selected disabled>{{ __('Beds') }}</option>

                            @for ($i = 1; $i <= $numOfBed; $i++)
                              <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                          </select>
                        </div>
                      </div>

                      <div class="col-lg-3">
                        <div class="input-wrap">
                          <button type="submit" class="btn filled-btn btn-block btn-black rounded-0">
                            {{ __('search') }} <i class="far fa-long-arrow-right"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>

                <div class="booking-shape-1">
                  <img class="lazy" data-src="{{ asset('assets/img/shape/01.png') }}" alt="shape">
                </div>
                <div class="booking-shape-2">
                  <img class="lazy" data-src="{{ asset('assets/img/shape/06.png') }}" alt="shape">
                </div>
                <div class="booking-shape-3">
                  <img class="lazy" data-src="{{ asset('assets/img/shape/07.png') }}" alt="shape">
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- Booking Search Form End -->
    @endif


    @if ($sections->featured_services_section == 1)
      <!-- Feature Service Section Start -->
      <section class="feature-section section-padding">
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

    @if ($sections->featured_rooms_section == 1)
      <!-- Latest Room Section Start -->
      <section class="latest-room section-padding">
        <div class="container">
          <!-- Section Title -->
          <div class="section-title text-center">
            @if (!empty($secHeading))
              <div class="row justify-content-center">
                <div class="col-lg-7">
                  <span class="title-top">{{ $secHeading->room_section_title }}</span>
                  <h1>{{ $secHeading->room_section_subtitle }}</h1>
                </div>
              </div>
            @endif
          </div>

          <!-- Single Room Box -->
          @if (count($roomInfos) == 0 || $roomFlag == 0)
            <div class="row text-center">
              <div class="col">
                <h3>{{ __('No Featured Room Found!') }}</h3>
              </div>
            </div>
          @else
            <div class="row">
              @foreach ($roomInfos as $roomInfo)
                @if (!empty($roomInfo->room))
                  <div class="col-lg-4 col-md-6">
                    <div class="room-box text-center">
                      <div class="room-img">
                        <img class="lazy" data-src="{{ asset('assets/img/rooms/' . $roomInfo->room->featured_img) }}"
                          alt="room">
                      </div>
                      <div class="room-content">
                        <i class="far fa-stars"></i>
                        <h5>
                          <a
                            href="{{ route('room_details', ['id' => $roomInfo->room_id, 'slug' => $roomInfo->slug]) }}">{{ strlen($roomInfo->title) > 25 ? mb_substr($roomInfo->title, 0, 25, 'utf-8') . '....' : $roomInfo->title }}</a>
                        </h5>
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
                        <p class="price">
                          {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                          {{ $roomInfo->room->rent }}
                          {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                        </p>
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          @endif
        </div>

        <!-- Design Shape -->
        <div class="shape-one">
          <img class="lazy" data-src="{{ asset('assets/img/shape/08.png') }}" alt="shape">
        </div>
        <div class="shape-two">
          <img class="lazy" data-src="{{ asset('assets/img/shape/03.png') }}" alt="shape">
        </div>
        <div class="shape-three"></div>
      </section>
      <!-- Latest Room Section End -->
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
                <h3>{{ __('No Featured Package Found') . '!' }}</h3>
              </div>
            </div>
          @else
            <div class="row">
              @foreach ($packageInfos as $packageInfo)
                @if (!empty($packageInfo->package))
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
                                  ({{ __($packageInfo->package->pricing_type) }})
                                </span></li>
                            @else
                              <li><span><i
                                    class="fas fa-comment-dollar"></i><strong>{{ __('Package Price') . ' : ' }}</strong>
                                  {{ __('Negotiable') }}</span></li>
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


    <section class="feature-section-two">
      @if ($sections->faq_section == 1)
        <!-- Why Choose US/FAQ Start -->
        <div class="wcu-section">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <!-- Section Title -->
                <div class="section-title">
                  @if (!empty($secHeading))
                    <span class="title-top">{{ convertUtf8($secHeading->faq_section_title) }}</span>
                    <h1>{{ convertUtf8($secHeading->faq_section_subtitle) }}</h1>
                  @endif
                </div>

                @if (count($faqs) > 0)
                  <div class="feature-accordion accordion" id="faqAccordion">
                    @foreach ($faqs as $faq)
                      <div class="card">
                        <div class="card-header ">
                          <button type="button" class="{{ $loop->first ? 'active-accordion' : '' }}"
                            data-toggle="collapse" data-target="{{ '#faq' . $faq->id }}">
                            {{ $faq->question }}
                            <span class="open-icon"><i class="far fa-eye-slash"></i></span>
                            <span class="close-icon"><i class="far fa-eye"></i></span>
                          </button>
                        </div>

                        <div id="{{ 'faq' . $faq->id }}" class="collapse {{ $loop->first ? 'show' : '' }}"
                          data-parent="#faqAccordion">
                          <div class="card-body">{{ $faq->answer }}</div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>

              <div class="col-lg-6">
                <div class="feature-accordion-img text-right">
                  @if (!empty($secHeading->faq_section_image))
                    <img class="lazy"
                      data-src="{{ asset('assets/img/faq_section/' . $secHeading->faq_section_image) }}"
                      alt="image">
                  @endif

                  <div class="degin-shape">
                    <div class="shape-one">
                      <img class="lazy" data-src="{{ asset('assets/img/shape/11.png') }}" alt="shape">
                    </div>
                    <div class="shape-two">
                      <img class="lazy" data-src="{{ asset('assets/img/shape/12.png') }}" alt="shape">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Why Choose US/FAQ End -->
      @endif

      @if ($sections->intro_section == 1)
        <!-- Intro Section Start -->
        <div class="featured-slider position-relative section-padding">
          <div class="container-fluid">
            <div class="row no-gutters">
              <div class="col-xl-10">
                <div class="feature-slide-wrap" id="featureSlideActive">
                  <div class="single-feature-slide">
                    @if (!empty($intro))
                      <img class="lazy f-big-image"
                        data-src="{{ asset('assets/img/intro_section/' . $intro->intro_img) }}" alt="Image">
                    @endif

                    <div class="row no-gutters justify-content-end">
                      <div class="col-xl-5 col-lg-8 col-md-8">
                        <div class="f-desc">
                          <h1>{{ !empty($intro->intro_secondary_title) ? $intro->intro_secondary_title : '' }}</h1>
                          <p>{{ !empty($intro->intro_text) ? $intro->intro_text : '' }}</p>
                          <div class="line"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Intro Section End -->
      @endif
    </section>

    @if ($sections->statistics_section == 1)
      <!-- CounterUp Start -->
      <section class="counter-up primary-bg lazy" data-bg="{{ asset('assets/img/counter-bg.jpg') }}">
        <div class="container">
          @if (count($counterInfos) == 0)
            <div class="row text-center">
              <div class="col">
                <h3>{{ __('No Counter Information Found!') }}</h3>
              </div>
            </div>
          @else
            <div class="row">
              @foreach ($counterInfos as $counterInfo)
                <div class="col-lg-3 col-md-6">
                  <div class="counter-box style-two">
                    <div class="fact-icon">
                      <i class="{{ $counterInfo->icon }}"></i>
                    </div>
                    <p class="fact-num"><span class="counter-number">{{ $counterInfo->amount }}</span></p>
                    <p>{{ $counterInfo->title }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </section>
      <!-- CounterUp End -->
    @endif

    @if ($sections->video_section == 1)
      <!-- Call To Action Start -->
      <section class="cta-section bg-img-center lazy {{ $websiteInfo->home_version == 'parallax' ? 'parallax' : '' }}"
        data-bg="{{ asset('assets/img/booking-img.jpg') }}">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-10">
              <div class="cta-left-content">
                @if (!empty($secHeading))
                  <span>{{ convertUtf8($secHeading->booking_section_title) }}</span>
                  <h1>{{ convertUtf8($secHeading->booking_section_subtitle) }}</h1>
                  <a href="{{ $secHeading->booking_section_button_url }}" class="btn filled-btn">
                    {{ $secHeading->booking_section_button }} <i class="far fa-long-arrow-right"></i>
                  </a>
                @endif
              </div>
            </div>

            <div class="col-md-2">
              @if (!empty($secHeading))
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

    @if ($sections->testimonials_section == 1)
      <!-- Feedback/Testimonial Section Start -->
      <section class="feedback-section-two section-padding">
        <div class="container">
          <!-- Section Title -->
          <div class="section-title text-center">
            @if (!empty($secHeading))
              <div class="row justify-content-center">
                <div class="col-lg-7">
                  <span class="title-top">{{ $secHeading->testimonial_section_title }}</span>
                  <h1>{{ $secHeading->testimonial_section_subtitle }}</h1>
                </div>
              </div>
            @endif
          </div>

          @if (count($testimonials) == 0)
            <div class="row text-center">
              <div class="col">
                <h3>{{ __('No Testimonial Found!') }}</h3>
              </div>
            </div>
          @else
            <div class="feedback-slider-two" id="feedSliderTwo">
              @foreach ($testimonials as $testimonial)
                {{-- show only those testimonials which has client image and designation --}}
                <div class="single-feedback-slide">
                  <div class="row align-items-center">
                    <div class="col-lg-6">
                      <div class="client-big-img">
                        @if (!empty($secHeading->testimonial_section_image))
                          <img class="lazy"
                            data-src="{{ asset('assets/img/testimonial_section/' . $secHeading->testimonial_section_image) }}"
                            alt="">
                        @endif
                      </div>
                    </div>

                    <div class="col-lg-5 offset-lg-1">
                      <div class="feedback-desc">
                        <div class="feedback-client-desc d-flex align-items-center">
                          @if (!empty($testimonial->client_image))
                            <div class="client-img">
                              <img class="lazy"
                                data-src="{{ asset('assets/img/testimonial_section/' . $testimonial->client_image) }}"
                                alt="">
                            </div>
                          @endif
                          <div class="client-name">
                            <h3>{{ convertUtf8($testimonial->client_name) }}</h3>
                            @if (!empty($testimonial->client_designation))
                              <span class="client-job">{{ convertUtf8($testimonial->client_designation) }}</span>
                            @endif
                          </div>
                        </div>
                        <p>{{ $testimonial->comment }}</p>
                        <span class="quote-icon"><img class="lazy"
                            data-src="{{ asset('assets/img/icons/quote.png') }}" alt="quote"></span>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </section>
      <!-- Feedback/Testimonial Section End -->
    @endif

    @if ($sections->blogs_section == 1)
      <!-- Latest Blog Start -->
      <section class="latest-blog section-padding section-bg">
        <div class="container">
          <!-- Section Title -->
          <div class="section-title text-center">
            @if (!empty($secHeading))
              <div class="row justify-content-center">
                <div class="col-lg-7">
                  <span class="title-top">{{ convertUtf8($secHeading->blog_section_title) }}</span>
                  <h1>{{ convertUtf8($secHeading->blog_section_subtitle) }}</h1>
                </div>
              </div>
            @endif
          </div>

          @if (count($blogInfos) == 0)
            <div class="row text-center">
              <div class="col">
                <h3>{{ __('No Latest Blog Found!') }}</h3>
              </div>
            </div>
          @else
            <div class="row">
              @foreach ($blogInfos as $blogInfo)
                <div class="col-lg-4 col-md-6 col-sm-6 order-lg-1 order-sm-2">
                  <div
                    class="single-latest-blog wow @if ($loop->iteration == 1) fadeIn
                  @elseif ($loop->iteration == 2) fadeInUp
                  @elseif ($loop->iteration == 3) fadeIn @endif animated"
                    data-wow-duration="1500ms"
                    data-wow-delay="@if ($loop->iteration == 1) 400ms
                  @elseif ($loop->iteration == 2) 600ms
                  @elseif ($loop->iteration == 3) 800ms @endif">
                    <div class="blog-img">
                      <img class="lazy" data-src="{{ asset('assets/img/blogs/' . $blogInfo->blog->blog_img) }}"
                        alt="blog image">
                    </div>
                    <div class="latest-blog-desc">
                      <span class="post-date"><i
                          class="far fa-calendar-alt"></i>{{ date_format($blogInfo->blog->created_at, 'd M Y') }}</span>
                      <h6>
                        {{ convertUtf8($blogInfo->title) }}
                      </h6>
                      <a href="{{ route('blog_details', ['id' => $blogInfo->blog_id, 'slug' => $blogInfo->slug]) }}"
                        class="read-more">
                        {{ __('read more') }} <i class="far fa-long-arrow-right"></i>
                      </a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </section>
      <!-- Latest Blog End -->
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
