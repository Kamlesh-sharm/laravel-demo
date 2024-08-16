<section class="hero-section slider-two" id="secondSlider">
    @if (count($sliders) != 0)
      @foreach ($sliders as $slider)
      <div>
          <div
            class="single-hero-slide bg-img-center d-flex align-items-center lazy"
            data-bg="{{ asset('assets/img/hero_slider/' . $slider->img) }}"
          >
            <div class="container">
              <div class="row">
                <div class="col-lg-7 col-md-10">
                  <div class="slider-text">
                    <h1 data-animation="fadeInDown" data-delay=".3s">
                      {{-- Luxury Hotel <br> & Room Service <br>Agency --}}
                      {{ convertUtf8($slider->title) }}
                    </h1>
                    <p data-animation="fadeInLeft" data-delay=".5s">
                      {{ convertUtf8($slider->subtitle) }}
                    </p>
                    <a class="btn filled-btn" href="{{ $slider->btn_url }}" data-animation="fadeInUp" data-delay=".8s">
                      {{ convertUtf8($slider->btn_name) }} <i class="far fa-long-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      @endforeach
    @else
        <div class="bg-light pt-70 pb-70 text-center">
            <h3>{{__('No Slider Found!')}}</h4>
        </div>
    @endif
  </section>
