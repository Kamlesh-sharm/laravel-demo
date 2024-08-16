<!-- Hero Section Start -->
<section class="hero-section">
  <div id="hero-home-5" class="single-hero-slide bg-img-center d-flex align-items-center text-center lazy"
    data-bg="{{ asset('assets/img/hero_static/' . $img) }}">
    <div id="bgndVideo"
      data-property="{videoURL:'{{ $websiteInfo->hero_video_link }}',containment:'#hero-home-5', quality:'default', autoPlay:true, loop:true, mute:true, startAt:0, opacity:1}">
    </div>
    <div class="container">
      <div class="slider-text">
        <span class="small-text" data-animation="fadeInDown"
          data-delay=".3s">{{ $title ? convertUtf8($title) : __('Welcome to Hotelia') }}</span>
        <h1 data-animation="fadeInLeft" data-delay=".6s">{{ $subtitle ? convertUtf8($subtitle) : __('Luxury Living') }}
        </h1>
        <a class="btn filled-btn" href="{{ $btnUrl }}" data-animation="fadeInUp" data-delay=".9s">
          {{ $btnName ? convertUtf8($btnName) : __('GET STARTED') }} <i class="far fa-long-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>
<!-- Hero Section End -->
