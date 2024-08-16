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
<!-- main area start here  -->
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


      <!-- feature area start here  -->
      @if ($sections->facilities_section == 1)
      <div class="bd-feature bg-common-black pt-145 p-relative pb-90 mpt-80">
         <div class="container">
            <div class="bd-feature__list pt-0">
               <div class="row wow fadeInUp" data-wow-delay=".5s"
                  style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInUp;">
                  @if (count($facilities) > 0)
                  <div class="col-12">
                     <div class="bd-feature__list-content">
                     @foreach ($facilities as $facility)
                        <div class="bd-feature__list-item ryl-up-down-anim mb-40 is-white">
                            <i class="{{ $facility->facility_icon }}"></i>
                            <p>{{ convertUtf8($facility->facility_title) }}</p>
                            <p class="d-none">{{ $facility->facility_text }}</p>
                        </div>
                     @endforeach
                     </div>
                  </div>
                  @endif
               </div>
            </div>
         </div>
      </div>
      @endif
      <!-- feature area end here  -->

      <!-- about area start  -->
      <section class="bd-about__area pt-150 pb-150 bg-theme-2">
         <div class="container">
            @if ($sections->intro_section == 1)
            <div class="row align-items-center g-4 g-lg-0 mb-5 mb-lg-0 wow fadeInUp" data-wow-delay=".5s"
               style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInUp;">
               <div class="col-lg-6">
                  <div class="section-image about-4__img">
                    @if (!is_null($intro))
                        <img class="lazy img-full" data-src="{{ asset('assets/img/intro_section/' . $intro->intro_img) }}"
                        alt="image">
                    @endif
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="section-content p-lg-5">
                  @if (!is_null($intro))
                     <div class="bd-section__title-wrapper">
                        <p class="bd-section__subtitle mb-20">{{ $intro->intro_primary_title }}</p>
                        <h2 class="bd-section__title bd-facility-title mb-30">{{ $intro->intro_secondary_title }}</h2>
                        <p class="mb-30">{{ $intro->intro_text }}</p>
                        <div class="section-btn">
                           <a href=" about.html" class="bd-btn theme-btn">
                              About us <span><i class="fa-regular fa-arrow-right-long"></i></span>
                           </a>
                        </div>
                     </div>
                  @endif
                  </div>
               </div>
            </div>
            @endif
            @if ($sections->facilities_section == 1)
            <div class="row align-items-center g-4 g-lg-0 mb-0 mb-lg-0 wow fadeInUp" data-wow-delay=".5s"
               style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInUp;">
               <div class="col-lg-6 order-lg-2">
                @if (!is_null($secHeading))
                  <div class="section-image about-4__img">
                     <img class="lazy img-full"
                    data-src="{{ asset('assets/img/facility_section/' . $secHeading->facility_section_image) }}"
                    alt="image">
                  </div>
                @endif
               </div>
               <div class="col-lg-6 order-lg-1">
                  <div class="section-content p-lg-5">
                  @if (!is_null($secHeading))
                     <div class="bd-section__title-wrapper">
                        <p class="bd-section__subtitle mb-20">{{ convertUtf8($secHeading->facility_section_title) }}</p>
                        <h2 class="bd-section__title bd-facility-title mb-30">{{ convertUtf8($secHeading->facility_section_subtitle) }}</h2>
                        <p class="mb-30">Our platform offers an exquisite tapestry of indulgence, where travelers from both markets benefit from
                            the finest hospitality offerings. Inspired by a passion for travel and a commitment to elevating
                             the booking experience, Reserved Destinations eliminates the hassle of planning a holiday, ensuring
                              that every journey is an exciting and stress-free escape.</p>
                        <div class="section-btn">
                           <a href=" rooms.html" class="bd-btn theme-btn">
                              Choose room <span><i class="fa-regular fa-arrow-right-long"></i></span>
                           </a>
                        </div>
                     </div>
                  @endif
                  </div>
               </div>
            </div>
            @endif
         </div>
      </section>
      <!-- about area end  -->

      <!-- amenities area start  -->
      <section class="amenities-area pt-150 pb-150">
         <div class="container">
            <div class="row justify-content-center wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="col-lg-10">
                  <div class="bd-section__title-wrapper text-center">
                     <p class="bd-section__subtitle mb-20">book now</p>
                     <h2 class=" bd-section__title mb-75 mbs-30">Welcome To Reserved Destinations
                        <br> Book Your Stay Today.
                     </h2>
                  </div>
               </div>
            </div>
         </div>
         <div
            class="swiper-container bd-amenities-slider-two wow fadeInUp swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events"
            data-wow-delay=".5s" style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
            <div class="swiper-wrapper amenities-slider-two-wrapper" id="swiper-wrapper-14abab359c584e9a"
               aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
               <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 6"
                  style="width: 429.667px; margin-right: 30px;">
                  <div class="amenities__box">
                     <div class="amenities__img">
                        <a href=" room-details.html"><img src="./assets/images/pool.jpg" alt="image not found"></a>
                     </div>
                     <div class="amenities__desc style-2">
                        <h4 class="amenities__title bg-theme-1 px-3"><a href=" room-details.html">Swimming Pool</a>
                        </h4>
                     </div>
                  </div>
               </div>
               <div class="swiper-slide swiper-slide-next" role="group" aria-label="2 / 6"
                  style="width: 429.667px; margin-right: 30px;">
                  <div class="amenities__box">
                     <div class="amenities__img">
                        <a href=" room-details.html"><img src="./assets/images/gym.jpg" alt="image not found"></a>
                     </div>
                     <div class="amenities__desc style-2">
                        <h4 class="amenities__title bg-theme-1 px-3"><a href=" room-details.html">Fitness Center</a>
                        </h4>
                     </div>
                  </div>
               </div>
               <div class="swiper-slide" role="group" aria-label="3 / 6" style="width: 429.667px; margin-right: 30px;">
                  <div class="amenities__box">
                     <div class="amenities__img">
                        <a href=" room-details.html"><img src="./assets/images/sauna.jpg" alt="image not found"></a>
                     </div>
                     <div class="amenities__desc style-2">
                        <h4 class="amenities__title bg-theme-1 px-3"><a href=" room-details.html">Sauna</a></h4>
                     </div>
                  </div>
               </div>

               <div class="swiper-slide" role="group" aria-label="4 / 6" style="width: 429.667px; margin-right: 30px;">
                  <div class="amenities__box">
                     <div class="amenities__img">
                        <a href=" room-details.html"><img src="./assets/images/steam-room.jpg"
                              alt="image not found"></a>
                     </div>
                     <div class="amenities__desc style-2">
                        <h4 class="amenities__title bg-theme-1 px-3"><a href=" room-details.html">Steam Room</a></h4>
                     </div>
                  </div>
               </div>
               <div class="swiper-slide" role="group" aria-label="5 / 6" style="width: 429.667px; margin-right: 30px;">
                  <div class="amenities__box">
                     <div class="amenities__img">
                        <a href=" room-details.html"><img src="./assets/images/golf-course.jpg"
                              alt="image not found"></a>
                     </div>
                     <div class="amenities__desc style-2">
                        <h4 class="amenities__title bg-theme-1 px-3"><a href=" room-details.html">Golf Course</a></h4>
                     </div>
                  </div>
               </div>
               <div class="swiper-slide" role="group" aria-label="6 / 6" style="width: 429.667px; margin-right: 30px;">
                  <div class="amenities__box">
                     <div class="amenities__img">
                        <a href=" room-details.html"><img src="./assets/images/ennis-court.jpg"
                              alt="image not found"></a>
                     </div>
                     <div class="amenities__desc style-2">
                        <h4 class="amenities__title bg-theme-1 px-3"><a href=" room-details.html">Tennis Court</a></h4>
                     </div>
                  </div>
               </div>
            </div>

            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
         </div>
         <div class="bd-amenities-slider-two-nav mt-75 mts-30 wow fadeInUp" data-wow-delay=".5s"
            style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
            <div class="bd-amenities-slider-two-prev square-nav is-black swiper-button-disabled" tabindex="-1"
               role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-14abab359c584e9a"
               aria-disabled="true"><i class="fa-light fa-angle-left"></i>
            </div>
            <div class="bd-amenities-slider-two-next square-nav is-black" tabindex="0" role="button"
               aria-label="Next slide" aria-controls="swiper-wrapper-14abab359c584e9a" aria-disabled="false"><i
                  class="fa-light fa-angle-right"></i>
            </div>
         </div>
      </section>
      <!-- amenities area end  -->

      <!-- room area end here  -->
      @if ($sections->featured_rooms_section == 1)
      <section class="bd-room-area p-relative pt-150 pb-150 fix">
         <div class="bd-room__bg" data-background="./assets/images/bd-room.jpg"
            style="background-image: url('./assets/images/bd-room.jpg');"></div>
         <div class="container">
            <div class="row align-items-end mb-25 wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="col-xl-6 col-lg-6 mb-0">
                @if (!is_null($secHeading))
                  <div class="bd-section__title-wrapper is-white">
                     <p class="bd-section__subtitle mb-20">{{ convertUtf8($secHeading->room_section_title) }}</p>
                     <h2 class=" bd-section__title mb-50 mmb-30">{{ convertUtf8($secHeading->room_section_subtitle) }}</h2>
                     <p>{{ $secHeading->room_section_text }}</p>
                  </div>
                @endif
               </div>
               <div class="col-xl-6 col-lg-6 ">
                  <div class="section-btn d-flex justify-content-lg-end mb-50">
                     <a href=" rooms.html" class="bd-btn">
                        All rooms <span><i class="fa-regular fa-arrow-right-long"></i></span>
                     </a>
                  </div>
               </div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               @if (count($roomInfos) == 0 || $roomFlag == 0)
                <h3 class="text-center text-white">{{ __('No Featured Room Found!') }}</h3>
               @else
               <div class="swiper bd-room-slider-three swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">
                  <div class="swiper-wrapper" id="swiper-wrapper-8638fe4da08f3abc" aria-live="polite"
                     style="transform: translate3d(0px, 0px, 0px);">
                    @foreach ($roomInfos as $roomInfo)
                    @if (!is_null($roomInfo->room))
                     <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 6"
                        style="width: 540px; margin-right: 30px;">
                        <div class="bd-room mb-0">
                           <div class="bd-room__content">
                              <h4 class="bd-room__title mb-20"><a href="{{ route('room_details', ['id' => $roomInfo->room_id, 'slug' => $roomInfo->slug]) }}">{{ convertUtf8($roomInfo->title) }}</a></h4>
                              <div class="bd-room__price mb-30">
                                 <p>{{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                    {{ $roomInfo->room->rent }}
                                    {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                    / {{ __('Night') }} <span>/NIGHT</span></p>
                              </div>
                              <div class="bd-room__thumb-wrap mb-30">
                                 <div class="bd-room__thumb">
                                    <img class="lazy" data-src="{{ asset('assets/img/rooms/' . $roomInfo->room->featured_img) }}" alt="">
                                 </div>
                                 <div class="bd-room__details">
                                    <p>{{ $roomInfo->summary }}</p>
                                    <div class="bd-room__list">
                                       <div class="bd-room__list-item">
                                          <i class="flaticon-bed"></i>
                                          <p>{{ $roomInfo->room->bed }}
                                          {{ $roomInfo->room->bed == 1 ? __('Bed') : __('Beds') }}</p>
                                       </div>
                                       <div class="bd-room__list-item">
                                          <i class="flaticon-bath"></i>
                                          <p>{{ $roomInfo->room->bath }}
                                          {{ $roomInfo->room->bath == 1 ? __('Bath') : __('Baths') }}</p>
                                       </div>
                                       @if (!empty($roomInfo->room->max_guests))
                                       <div class="bd-room__list-item">
                                          <i class="flaticon-users"></i>
                                          <p>{{ $roomInfo->room->max_guests }}
                                          {{ $roomInfo->room->max_guests == 1 ? __('Guest') : __('Guests') }}</p>
                                       </div>
                                       @endif
                                       <div class="bd-room__list-item">
                                          <i class="flaticon-swimming-pool"></i>
                                          <p>{{ $roomInfo->room->bath }}
                                          {{ $roomInfo->room->bath == 1 ? __('Bath') : __('Baths') }}</p>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="bd-room__btn">
                                 <a href=" booking-form.html"><span>book now</span> <i
                                       class="fa-regular fa-arrow-right-long"></i></a>
                              </div>
                           </div>
                        </div>
                     </div>
                    @endif
                    @endforeach
                  </div>
                  <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
               </div>
               @endif
            </div>
            <div class="bd-room-slider-three-nav mt-35 mts-30 d-sm-none wow fadeInUp animated" data-wow-delay=".5s"
               style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInUp;">
               <div class="bd-room-slider-three-prev square-nav swiper-button-disabled" tabindex="-1" role="button"
                  aria-label="Previous slide" aria-controls="swiper-wrapper-8638fe4da08f3abc" aria-disabled="true"><i
                     class="fa-light fa-angle-left"></i>
               </div>
               <div class="bd-room-slider-three-next square-nav" tabindex="0" role="button" aria-label="Next slide"
                  aria-controls="swiper-wrapper-8638fe4da08f3abc" aria-disabled="false"><i
                     class="fa-light fa-angle-right"></i>
               </div>
            </div>
         </div>
      </section>
      @endif
      <!-- room area end here  -->

      <!-- service area start here  -->
      @if ($sections->featured_services_section == 1)
      <section class="bd-service-area fix pt-135 pb-150">
         <div class="container">
            <div class="row align-items-end wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               @if (!empty($secHeading))
               <div class="col-xl-8 col-lg-8">
                  <div class="bd-section__title-wrapper">
                     <p class="bd-section__subtitle mb-20">{{ convertUtf8($secHeading->service_section_title) }}</p>
                     <h2 class="bd-section__title mb-55  mmb-30">{{ convertUtf8($secHeading->service_section_subtitle) }}</h2>
                  </div>
               </div>
               @endif
               <div class="col-xl-4 col-lg-4">
                  <div class="bd-service__pagination-wrap d-flex justify-content-lg-end mb-25">
                     <div
                        class="bd-service-pagination bd-swiper-pagination swiper-pagination-clickable swiper-pagination-bullets">
                        <span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0"
                           role="button" aria-label="Go to slide 1"></span><span class="swiper-pagination-bullet"
                           tabindex="0" role="button" aria-label="Go to slide 2"></span></div>
                  </div>
               </div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="col-12">
               @if (count($serviceInfos) == 0 || $serviceFlag == 0)
                    <div class="row text-center">
                        <div class="col">
                            <h3>{{ __('No Featured Service Found!') }}</h3>
                        </div>
                    </div>
                @else
                  <div class="swiper-container bd-service-active swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">
                     <div class="swiper-wrapper" id="swiper-wrapper-ea103264b5f45410f4" aria-live="polite">
                    @foreach ($serviceInfos as $serviceInfo)
                    @if (!is_null($serviceInfo->service))
                        <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 5"
                           style="width: 350px; margin-right: 30px;">
                           <div class="bd-service">
                              <div class="bd-service__bg" data-bg="{{ asset('assets/img/hero_static/' . $img) }}"
                                 style="background-image: url(.assets/img/service/1.jpg);"></div>
                              <div class="bd-service__content">
                                <div class="service-icon">
                                    <i class="{{ $serviceInfo->service->service_icon }}"></i>
                                </div>
                                @if ($serviceInfo->service->details_page_status == 1)<h4 class="bd-service__title"><a href="{{ route('service_details', ['id' => $serviceInfo->service_id, 'slug' => $serviceInfo->slug]) }}">{{ convertUtf8($serviceInfo->title) }}</a></h4>@endif
                                 <span class="bd-service__price">{{ strlen($serviceInfo->summary) > 35 ? substr($serviceInfo->summary, 0, 35) . '...' : $serviceInfo->summary }}</span>
                              </div>
                           </div>
                        </div>
                    @endif
                    @endforeach
                     </div>
                     <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                  </div>
                @endif
               </div>
            </div>
         </div>
      </section>
      @endif
      <!-- service area end here  -->


      <!-- testimonial area start  -->
      @if ($sections->testimonials_section == 1)
      <section class="bd-testimonial-4__area pt-150 pb-150 p-relative">
         <div class="bd-testimonial-4__bg" data-background="../assets/images/award.jpg"
            style="background-image: url('../assets/images/award.jpg');"></div>
         <div class="container">
        @if (!empty($secHeading))
            <div class="row align-items-end wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="col-xl-12 col-lg-12">
                  <div class="bd-section__title-wrapper text-center is-white">
                     <p class="bd-section__subtitle mb-20">{{ convertUtf8($secHeading->testimonial_section_title) }}</p>
                     <h2 class=" bd-section__title mb-55 mmb-30">{{ convertUtf8($secHeading->testimonial_section_subtitle) }}</h2>
                  </div>
               </div>
            </div>
        @endif

        @if (count($testimonials) == 0)
            <div class="row text-center">
              <div class="col">
                <h3 class="text-white">{{ __('No Testimonial Found!') }}</h3>
              </div>
            </div>
        @else
            <div class="swiper-container bd-testimonial-4-active wow fadeInUp swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events"
               data-wow-delay=".5s" style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="swiper-wrapper" id="swiper-wrapper-9c101292b4fc3a18a" aria-live="off"
                  style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
                @foreach ($testimonials as $testimonial)
                  <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 6"
                     style="width: 350px; margin-right: 30px;">
                     <div class="bd-testimonial-4">

                        <div class="bd-testimonial-4__content">
                           <div class="bd-testimonial-4__quote">
                              <i class="flaticon-quote"></i>
                           </div>
                           <p>{{ $testimonial->comment }}</p>
                           <div class="bd-testimonial-4__rating d-flex justify-content-center">
                              <i class="fa-solid fa-star"></i>
                              <i class="fa-solid fa-star"></i>
                              <i class="fa-solid fa-star"></i>
                              <i class="fa-solid fa-star"></i>
                              <i class="fa-solid fa-star"></i>
                           </div>
                           <div class="bd-testimonial-4__quote-2">
                              <i class="flaticon-quote"></i>
                           </div>
                           <div class="d-flex justify-content-center align-items-center">
                              <div class="bd-testimonial-4__thumb">
                                 <img src="./assets/images/user-1.jpg" alt="image not found">
                              </div>
                              <div class="bd-testimonial-4__client">
                                 <h5 class="bd-testimonial-4__client-name">{{ convertUtf8($testimonial->client_name) }}</h5>
                                 <span class="bd-testimonial-4__client-position d-none">Moscow / Russia</span>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                @endforeach
               </div>
               <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
            </div>
        @endif
         </div>

      </section>
      @endif
      <!-- testimonial area end  -->


      <!-- gallery area start  -->
      <section class="gallery-area pt-150 pb-130">
         <div class="container">
            <div class="row align-items-end wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="col-xl-12 col-lg-12">
                  <div class="bd-section__title-wrapper text-center">
                     <p class="bd-section__subtitle mb-20">Gallery</p>
                     <h2 class=" bd-section__title mb-55 mmb-30">Our Gallery
                     </h2>
                  </div>
               </div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="col-xl-12">
                  <div class="gallery__img-wrapper">
                     <div class="gallery__img">
                        <a href="./assets/images/main-1.jpg" class="popup-image"><img src="./assets/images/main-1.jpg"
                              alt="gallery-img"></a>
                     </div>
                     <div class="gallery__img">
                        <a href="./assets/images/main-2.jpg" class="popup-image"><img src="./assets/images/main-2.jpg"
                              alt="gallery-img"></a>
                     </div>
                     <div class="gallery__img">
                        <a href="./assets/images/main-4.jpg" class="popup-image"><img src="./assets/images/main-4.jpg"
                              alt="gallery-img"></a>
                     </div>
                     <div class="gallery__img">
                        <a href="./assets/images/main-5.jpg" class="popup-image"><img src="./assets/images/main-5.jpg"
                              alt="gallery-img"></a>
                     </div>
                     <div class="gallery__img">
                        <a href="./assets/images/main-6.jpg" class="popup-image"><img src="./assets/images/main-6.jpg"
                              alt="gallery-img"></a>
                     </div>
                     <div class="gallery__img">
                        <a href="./assets/images/main-9.jpg" class="popup-image"><img src="./assets/images/main-9.jpg"
                              alt="gallery-img"></a>
                     </div>
                     <div class="gallery__img">
                        <a href="./assets/images/main-10.jpg" class="popup-image"><img src="./assets/images/main-10.jpg"
                              alt="gallery-img"></a>
                     </div>
                     <div class="gallery__img">
                        <a href="./assets/images/main-15.jpg" class="popup-image"><img src="./assets/images/main-15.jpg"
                              alt="gallery-img"></a>
                     </div>
                     <div class="gallery__img">
                        <a href="./assets/images/main-19.jpg" class="popup-image"><img src="./assets/images/main-19.jpg"
                              alt="gallery-img"></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- gallery area end  -->

      <!-- offer area start here -->
      @if ($sections->featured_package_section == 1)
      <section class="bd-offer-area pt-150 pb-150 theme-bg-2">
         <div class="container">
            <div class="row align-items-center wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               @if (!empty($secHeading))
               <div class="col-md-8">
                  <div class="bd-section__title-wrapper">
                     <p class="bd-section__subtitle mb-20">{{ convertUtf8($secHeading->package_section_title) }}</p>
                     <h2 class="bd-section__title mb-55  mmb-30">{{ convertUtf8($secHeading->package_section_subtitle) }}</h2>
                  </div>
               </div>
               @endif
               <div class="col-md-4">
                  <div class="bd-offer-slider-nav mb-50 d-flex justify-content-md-end">
                     <div class="bd-offer-slider-prev square-nav swiper-button-disabled" tabindex="-1" role="button"
                        aria-label="Previous slide" aria-controls="swiper-wrapper-c853e22d522e22ff"
                        aria-disabled="true"><i class="fa-light fa-angle-left"></i></div>
                     <div class="bd-offer-slider-next square-nav" tabindex="0" role="button" aria-label="Next slide"
                        aria-controls="swiper-wrapper-c853e22d522e22ff" aria-disabled="false"><i
                           class="fa-light fa-angle-right"></i></div>
                  </div>
               </div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="col-12">
                  <div
                     class="swiper-container bd-offer-active mmt-20 swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">
                     <div class="swiper-wrapper" id="swiper-wrapper-c853e22d522e22ff" aria-live="polite"
                        style="transform: translate3d(0px, 0px, 0px);">
                        <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 5"
                           style="width: 255px; margin-right: 30px;">
                           <div class="bd-offer">
                              <div class="bd-offer__thumb p-relative">
                                 <img src="./assets/images/1(1).jpg" alt="image not found">
                                 <div class="bd-offer__meta">
                                    <span>25% off</span>
                                 </div>
                                 <div class="bd-offer__content-visble">
                                    <h4 class="bd-offer__title-2"><a href=" offer-details.html">bed and
                                          breakfast</a></h4>
                                 </div>
                                 <div class="bd-offer__content">
                                    <h4 class="bd-offer__title"><a href=" offer-details.html">bed and breakfast</a>
                                    </h4>
                                    <p>The Gage Hotel offers unforgettable food and drink options. A memorable
                                       stay with
                                       delicious
                                    </p>
                                    <div class="bd-offer__btn">
                                       <a href=" booking-form.html">Book Now<i
                                             class="fa-regular fa-angle-right"></i></a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                  </div>
               </div>
            </div>
         </div>
      </section>
      @endif
      <!-- offer area end here -->

      <!-- blog area start here  -->
      <section class="bd-blog-area pt-150 pb-150">
         <div class="container">
            <div class="row align-items-center wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="col-md-8">
                  <div class="bd-section__title-wrapper ">
                     <p class="bd-section__subtitle mb-20">News &amp; Blog</p>
                     <h2 class=" bd-section__title mb-55 mmb-10">Our latest news
                     </h2>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="bd-blog__btn mb-55">
                     <a href=" contact.html" class="bd-btn-2">View all blog <i
                           class="fa-regular fa-arrow-right-long"></i></a>
                  </div>
               </div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay=".5s"
               style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
               <div class="col-12">
                  <div
                     class="bd-blog-2-active swiper-container swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">
                     <div class="swiper-wrapper" id="swiper-wrapper-41f10c68e16b2576e" aria-live="polite"
                        style="transition-duration: 0ms; transform: translate3d(-1140px, 0px, 0px);">
                        <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-active"
                           data-swiper-slide-index="0" role="group" aria-label="1 / 9"
                           style="width: 350px; margin-right: 30px;">
                           <div class="bd-blog-4 d-flex align-items-end">
                              <div class="bd-blog-4__thumb">
                                 <img src="./assets/images/3(2).jpg" alt="image not found">
                              </div>
                              <div class="bd-blog-4__content">
                                 <div class="bd-blog-4__meta">
                                    <a href=" blog-details.html">
                                       25<br>jan
                                    </a>
                                 </div>
                                 <h4 class="bd-blog-4__title"><a href=" blog-details.html">
                                       Bye Bye Resort Fees - How to Fill Your
                                       Pending Revenue
                                    </a></h4>
                              </div>
                           </div>
                        </div>
                        <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-next"
                           data-swiper-slide-index="1" role="group" aria-label="2 / 9"
                           style="width: 350px; margin-right: 30px;">
                           <div class="bd-blog-4 d-flex align-items-end">
                              <div class="bd-blog-4__thumb">
                                 <img src="./assets/images/1(2).jpg" alt="image not found">
                              </div>
                              <div class="bd-blog-4__content">
                                 <div class="bd-blog-4__meta">
                                    <a href=" blog-details.html">
                                       25<br>jun
                                    </a>
                                 </div>
                                 <h4 class="bd-blog-4__title"><a href=" blog-details.html">
                                       Hyatt Pledges to Open 5,000 Rooms in Mexico’s Tulum
                                    </a></h4>
                              </div>
                           </div>
                        </div>
                        <div class="swiper-slide swiper-slide-duplicate swiper-slide-prev" data-swiper-slide-index="2"
                           role="group" aria-label="3 / 9" style="width: 350px; margin-right: 30px;">
                           <div class="bd-blog-4 d-flex align-items-end">
                              <div class="bd-blog-4__thumb">
                                 <img src="./assets/images/5(1).jpg" alt="image not found">
                              </div>
                              <div class="bd-blog-4__content">
                                 <div class="bd-blog-4__meta">
                                    <a href=" blog-details.html">
                                       25<br>feb
                                    </a>
                                 </div>
                                 <h4 class="bd-blog-4__title"><a href=" blog-details.html">
                                       Bye Bye Resort Fees - How to Fill Your
                                       Pending Revenue
                                    </a></h4>
                              </div>
                           </div>
                        </div>
                        <div class="swiper-slide swiper-slide-active" data-swiper-slide-index="0" role="group"
                           aria-label="4 / 9" style="width: 350px; margin-right: 30px;">
                           <div class="bd-blog-4 d-flex align-items-end">
                              <div class="bd-blog-4__thumb">
                                 <img src="./assets/images/3(2).jpg" alt="image not found">
                              </div>
                              <div class="bd-blog-4__content">
                                 <div class="bd-blog-4__meta">
                                    <a href=" blog-details.html">
                                       25<br>jan
                                    </a>
                                 </div>
                                 <h4 class="bd-blog-4__title"><a href=" blog-details.html">
                                       Bye Bye Resort Fees - How to Fill Your
                                       Pending Revenue
                                    </a></h4>
                              </div>
                           </div>
                        </div>
                        <div class="swiper-slide swiper-slide-next" data-swiper-slide-index="1" role="group"
                           aria-label="5 / 9" style="width: 350px; margin-right: 30px;">
                           <div class="bd-blog-4 d-flex align-items-end">
                              <div class="bd-blog-4__thumb">
                                 <img src="./assets/images/1(2).jpg" alt="image not found">
                              </div>
                              <div class="bd-blog-4__content">
                                 <div class="bd-blog-4__meta">
                                    <a href=" blog-details.html">
                                       25<br>jun
                                    </a>
                                 </div>
                                 <h4 class="bd-blog-4__title"><a href=" blog-details.html">
                                       Hyatt Pledges to Open 5,000 Rooms in Mexico’s Tulum
                                    </a></h4>
                              </div>
                           </div>
                        </div>
                        <div class="swiper-slide swiper-slide-duplicate-prev" data-swiper-slide-index="2" role="group"
                           aria-label="6 / 9" style="width: 350px; margin-right: 30px;">
                           <div class="bd-blog-4 d-flex align-items-end">
                              <div class="bd-blog-4__thumb">
                                 <img src="./assets/images/5(1).jpg" alt="image not found">
                              </div>
                              <div class="bd-blog-4__content">
                                 <div class="bd-blog-4__meta">
                                    <a href=" blog-details.html">
                                       25<br>feb
                                    </a>
                                 </div>
                                 <h4 class="bd-blog-4__title"><a href=" blog-details.html">
                                       Bye Bye Resort Fees - How to Fill Your
                                       Pending Revenue
                                    </a></h4>
                              </div>
                           </div>
                        </div>
                        <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-active"
                           data-swiper-slide-index="0" role="group" aria-label="7 / 9"
                           style="width: 350px; margin-right: 30px;">
                           <div class="bd-blog-4 d-flex align-items-end">
                              <div class="bd-blog-4__thumb">
                                 <img src="./assets/images/3(2).jpg" alt="image not found">
                              </div>
                              <div class="bd-blog-4__content">
                                 <div class="bd-blog-4__meta">
                                    <a href=" blog-details.html">
                                       25<br>jan
                                    </a>
                                 </div>
                                 <h4 class="bd-blog-4__title"><a href=" blog-details.html">
                                       Bye Bye Resort Fees - How to Fill Your
                                       Pending Revenue
                                    </a></h4>
                              </div>
                           </div>
                        </div>
                        <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-next"
                           data-swiper-slide-index="1" role="group" aria-label="8 / 9"
                           style="width: 350px; margin-right: 30px;">
                           <div class="bd-blog-4 d-flex align-items-end">
                              <div class="bd-blog-4__thumb">
                                 <img src="./assets/images/1(2).jpg" alt="image not found">
                              </div>
                              <div class="bd-blog-4__content">
                                 <div class="bd-blog-4__meta">
                                    <a href=" blog-details.html">
                                       25<br>jun
                                    </a>
                                 </div>
                                 <h4 class="bd-blog-4__title"><a href=" blog-details.html">
                                       Hyatt Pledges to Open 5,000 Rooms in Mexico’s Tulum
                                    </a></h4>
                              </div>
                           </div>
                        </div>
                        <div class="swiper-slide swiper-slide-duplicate" data-swiper-slide-index="2" role="group"
                           aria-label="9 / 9" style="width: 350px; margin-right: 30px;">
                           <div class="bd-blog-4 d-flex align-items-end">
                              <div class="bd-blog-4__thumb">
                                 <img src="./assets/images/5(1).jpg" alt="image not found">
                              </div>
                              <div class="bd-blog-4__content">
                                 <div class="bd-blog-4__meta">
                                    <a href=" blog-details.html">
                                       25<br>feb
                                    </a>
                                 </div>
                                 <h4 class="bd-blog-4__title"><a href=" blog-details.html">
                                       Bye Bye Resort Fees - How to Fill Your
                                       Pending Revenue
                                    </a></h4>
                              </div>
                           </div>
                        </div>
                     </div>
                     <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                  </div>
               </div>
               <div class="bd-blog-2-nav mt-75 mts-30 wow fadeInUp" data-wow-delay=".5s"
                  style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
                  <div class="bd-blog-2-prev square-nav is-black" tabindex="0" role="button" aria-label="Previous slide"
                     aria-controls="swiper-wrapper-41f10c68e16b2576e"><i class="fa-light fa-angle-left"></i>
                  </div>
                  <div class="bd-blog-2-next square-nav is-black" tabindex="0" role="button" aria-label="Next slide"
                     aria-controls="swiper-wrapper-41f10c68e16b2576e"><i class="fa-light fa-angle-right"></i>
                  </div>
               </div>
            </div>

         </div>
      </section>
      <!-- blog area end here  -->


</main>
<!-- main area end here  -->
@endsection

@section('script')
  <script src="{{ asset('assets/js/home.js') }}"></script>
@endsection
