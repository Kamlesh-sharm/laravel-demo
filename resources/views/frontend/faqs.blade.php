@extends('frontend.layout')

@section('pageHeading')
  @if (!is_null($pageHeading))
    {{ $pageHeading->faqs_title }}
  @endif
@endsection

@php
    $metaKeys = !empty($seo->meta_keyword_faq) ? $seo->meta_keyword_faq : '';
    $metaDesc = !empty($seo->meta_description_faq) ? $seo->meta_description_faq : '';
@endphp

@section('meta-keywords', "$metaKeys")
@section('meta-description', "$metaDesc")

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center lazy" data-bg="{{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }}" >
      <div class="container">
        <div class="breadcrumb-content text-center">
          @if (!is_null($pageHeading))
            <h1>{{ convertUtf8($pageHeading->faqs_title) }}</h1>
          @endif

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            @if (!is_null($pageHeading))
              <li>{{ convertUtf8($pageHeading->faqs_title) }}</li>
            @endif
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- FAQ Start -->
    <section class="wcu-section section-padding">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <!-- Section Title -->
            <div class="section-title">
              @if (!is_null($secHeading))
                <span class="title-top">{{ convertUtf8($secHeading->faq_section_title) }}</span>
                <h1>{{ convertUtf8($secHeading->faq_section_subtitle) }}</h1>
              @endif
            </div>

            @if (count($faqs) == 0)
              <h3 class="text-center">{{ __('No FAQ Found!') }}</h3>
            @else
              <div class="feature-accordion accordion" id="faqAccordion">
                @foreach ($faqs as $faq)
                  <div class="card">
                    <div class="card-header">
                      <button
                        type="button"
                        class="{{ $loop->first ? 'active-accordion' : '' }}"
                        data-toggle="collapse"
                        data-target="{{ '#faq' . $faq->id }}"
                      >
                        {{ $faq->question }}
                        <span class="open-icon"><i class="far fa-eye-slash"></i></span>
                        <span class="close-icon"><i class="far fa-eye"></i></span>
                      </button>
                    </div>

                    <div
                      id="{{ 'faq' . $faq->id }}"
                      class="collapse {{ $loop->first ? 'show' : '' }}"
                      data-parent="#faqAccordion"
                    >
                      <div class="card-body">{{ $faq->answer }}</div>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

          <div class="col-lg-6">
            <div class="feature-accordion-img text-right">
              @if (!is_null($secHeading))
                <img class="lazy" data-src="{{ asset('assets/img/faq_section/' . $secHeading->faq_section_image) }}" alt="image">
              @endif

              <div class="degin-shape">
                <div class="shape-one">
                  <img src="{{ asset('assets/img/shape/11.png') }}" alt="shape">
                </div>

                <div class="shape-two">
                  <img src="{{ asset('assets/img/shape/12.png') }}" alt="shape">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- FAQ End -->
  </main>
@endsection
