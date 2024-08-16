@extends('frontend.layout')

@section('pageHeading')
  {{ __('Service Details') }}
@endsection

@php
  $metaKeys = !empty($details->meta_keywords) ? $details->meta_keywords : '';
  $metaDesc = !empty($details->meta_description) ? $details->meta_description : '';
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
          <h1>{{ strlen($details->title) > 30 ? mb_substr($details->title, 0, 30) . '...' : $details->title }}</h1>

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            <li>{{ __('Service Details') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="service-details-section pt-130 pb-130">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="service-sidebar">
              <div class="widgets service-cat">
                <h4 class="widget-title">{{ __('More Services') }}</h4>
                @if (count($moreServices) == 0)
                  <h5>{{ __('No More Service Found!') }}</h5>
                @else
                  <ul class="service-cat-list">
                    @foreach ($moreServices as $moreService)
                      @if (!is_null($moreService->service))
                        <li>
                          @php
                            $href = '#';
                            if ($moreService->service->details_page_status == 1) {
                                $href = route('service_details', ['id' => $moreService->service_id, 'slug' => $moreService->slug]);
                            }
                          @endphp
                          <a href="{{ $href }}">{{ $moreService->title }}<i class="far fa-angle-right"></i></a>
                        </li>
                      @endif
                    @endforeach
                  </ul>
                @endif
              </div>
            </div>
          </div>

          <!-- Service Details Section Start -->
          <div class="col-lg-8">
            <div class="service-details">
              <h2 class="title">{{ convertUtf8($details->title) }}</h2>
              <p>{{ $details->summary }}</p>
              <div class="summernote-content">
                {!! $details->content !!}
              </div>
            </div>
          </div>
          <!-- Service Details Section End -->
        </div>
      </div>
    </section>
  </main>
@endsection
