@extends('frontend.layout')

@section('pageHeading')
  @if (!is_null($pageHeading))
    {{ $pageHeading->services_title }}
  @endif
@endsection

@php
    $metaKeys = !empty($details->meta_keyword_services) ? $details->meta_keyword_services : '';
    $metaDesc = !empty($details->meta_description_services) ? $details->meta_description_services : '';
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
            <h1>{{ convertUtf8($pageHeading->services_title) }}</h1>
          @endif

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            @if (!is_null($pageHeading))
              <li>{{ convertUtf8($pageHeading->services_title) }}</li>
            @endif
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Service Section Start -->
    <section class="service-section section-padding section-bg">
      <div class="container">
        @if (count($serviceInfos) == 0)
          <div class="row text-center">
            <div class="col">
              <h3>{{ __('No Service Found!') }}</h3>
            </div>
          </div>
        @else
          <!-- Section Title -->
          <div class="section-title text-center">
            @if (!is_null($secHeading))
              <span class="title-top">{{ convertUtf8($secHeading->service_section_title) }}</span>
              <h1>{{ convertUtf8($secHeading->service_section_subtitle) }}</h1>
            @endif
          </div>

          <!-- Service Boxes -->
          <div class="row">
            @foreach ($serviceInfos as $serviceInfo)
              <div class="col-lg-4 col-md-6">
                <!-- Single Service -->
                <div
                  class="single-service-box service-white-bg text-center wow fadeIn animated"
                  data-wow-duration="1500ms"
                  data-wow-delay="{{$loop->iteration * 200}}ms"
                >
                  <span class="service-counter">{{ $loop->iteration }}</span>
                  <div class="service-icon">
                    <i class="{{ $serviceInfo->service_icon }}"></i>
                  </div>
                  <h4>{{ convertUtf8($serviceInfo->title) }}</h4>
                  <p>{{ strlen($serviceInfo->summary) > 35 ? substr($serviceInfo->summary, 0, 35) . '...' : $serviceInfo->summary }}</p>
                  @if ($serviceInfo->details_page_status == 1)
                    <a href="{{ route('service_details', ['id' => $serviceInfo->service_id, 'slug' => $serviceInfo->slug]) }}" class="read-more">
                      {{ __('read more') }} <i class="far fa-long-arrow-right"></i>
                    </a>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </section>
    <!-- Service Section End -->
  </main>
@endsection
