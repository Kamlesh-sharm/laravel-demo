<section class="hero-section slider-two">
  <div id="particles-js" class="single-hero-slide bg-img-center d-flex align-items-center lazy"
    data-bg="{{ asset('assets/img/hero_static/' . $img) }}">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 col-md-10">
          <div class="slider-text">
            <h1 data-animation="fadeInDown" data-delay=".3s">
              {{ $title ? convertUtf8($title) : __('Welcome to Hotelia') }}
            </h1>
            <p data-animation="fadeInLeft" data-delay=".5s">
              {{ $subtitle ? convertUtf8($subtitle) : __('Luxury Living') }}
            </p>
            <a class="btn filled-btn" href="{{ $btnUrl }}" data-animation="fadeInUp" data-delay=".8s">
              {{ $btnName ? convertUtf8($btnName) : __('GET STARTED') }} <i class="far fa-long-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
