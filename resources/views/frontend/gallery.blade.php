@extends('frontend.layout')

@section('pageHeading')
  @if (!is_null($pageHeading))
    {{ $pageHeading->gallery_title }}
  @endif
@endsection

@php
    $metaKeys = !empty($seo->meta_keyword_gallery) ? $seo->meta_keyword_gallery : '';
    $metaDesc = !empty($seo->meta_description_gallery) ? $seo->meta_description_gallery : '';
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
            <h1>{{ convertUtf8($pageHeading->gallery_title) }}</h1>
          @endif

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            @if (!is_null($pageHeading))
              <li>{{ convertUtf8($pageHeading->gallery_title) }}</li>
            @endif
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Gallery Start -->
    <section class="gallery-wrap section-padding">
      <div class="container">
        <!-- if category is null then no gallery is available -->
        @if (count($categories) == 0 || count($galleryInfos) == 0)
          <div class="row text-center">
            <div class="col">
              <h3>{{ __('No Gallery Found!') }}</h3>
            </div>
          </div>
        @else
          <div class="gallery-filter text-center">
            <ul class="list-inline">
              <li class="active" data-filter="*">{{ __('Show All') }}</li>
              @foreach ($categories as $category)
                @php
                  $filterValue = '.' . strtolower($category->name);

                  if (str_contains($filterValue, ' ')) {
                    $filterValue = str_replace(' ', '-', $filterValue);
                  }
                @endphp

                <li data-filter="{{ $filterValue }}">{{ convertUtf8($category->name) }}</li>
              @endforeach
            </ul>
          </div>

          <div class="gallery-items">
            <div class="row gallery-filter-items">
              @foreach ($galleryInfos as $galleryInfo)
                <!-- Single Item -->
                @php
                  $galleryCategory = $galleryInfo->galleryCategory()->first();
                  $categoryName = strtolower($galleryCategory->name);

                  if (str_contains($categoryName, ' ')) {
                    $categoryName = str_replace(' ', '-', $categoryName);
                  }
                @endphp

                <div class="col-lg-4 col-md-6 col-sm-6 {{ $categoryName }}">
                  <a class="gallery-item lazy bg-light d-block" href="{{ asset('assets/img/gallery/' . $galleryInfo->gallery_img) }}" data-bg="{{ asset('assets/img/gallery/' . $galleryInfo->gallery_img) }}">
                    <div class="gallery-content">
                      <h3>{{ convertUtf8($galleryInfo->title) }}</h3>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </section>
    <!-- Gallery End -->
  </main>
@endsection
