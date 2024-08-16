    <!-- Hero Section Start -->
    <section class="hero-section" id="heroSlideActive">
        @if (count($sliders) != 0)
          @foreach ($sliders as $slider)
          <div>
              <div
                class="single-hero-slide bg-img-center d-flex align-items-center text-center lazy"
                data-bg="{{ asset('assets/img/hero_slider/' . $slider->img) }}"
              >
                <div class="container">
                  <div class="slider-text">
                    <span class="small-text" data-animation="fadeInDown" data-delay=".3s">{{ convertUtf8($slider->title) }}</span>
                    <h1 data-animation="fadeInLeft" data-delay=".6s">{{ convertUtf8($slider->subtitle) }}</h1>
                    <a class="btn filled-btn" href="{{ $slider->btn_url }}" data-animation="fadeInUp" data-delay=".9s">
                      {{ convertUtf8($slider->btn_name) }} <i class="far fa-long-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
          </div>
          @endforeach
        @else
            <div class="bg-light pt-70 pb-130 text-center">
                <h3>{{__('No Slider Found!')}}</h4>
            </div>
        @endif
      </section>
      <!-- Hero Section End -->
