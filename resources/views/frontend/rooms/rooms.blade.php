@extends('frontend.layout')

@section('pageHeading')
  @if (!is_null($pageHeading))
    {{ $pageHeading->rooms_title }}
  @endif
@endsection

@php
    $metaKeywords = !empty($seo->meta_keyword_rooms) ? $seo->meta_keyword_rooms : '';
    $metaDesc = !empty($seo->meta_description_rooms) ? $seo->meta_description_rooms : '';
@endphp

@section('meta-keywords', "$metaKeywords")
@section('meta-description', "$metaDesc")

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center lazy" data-bg="{{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }}" >
      <div class="container">
        <div class="breadcrumb-content text-center">
          @if (!is_null($pageHeading))
            <h1>{{ convertUtf8($pageHeading->rooms_title) }}</h1>
          @endif

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            @if (!is_null($pageHeading))
              <li>{{ convertUtf8($pageHeading->rooms_title) }}</li>
            @endif
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- All Rooms Section Start -->
    <section class="rooms-warp list-view section-bg section-padding">
      <div class="container">

        <div class="row">
            @if ($websiteInfo->room_category_status == 1)
            <div class="col-12">
              <div class="filter-view">
                <ul>
                    <li @if(empty(request()->input('category'))) class="active-f-view" @endif><a href="{{route('rooms')}}">{{ __('All') }}</a></li>
                    @foreach ($categories as $cat)
                    <li @if(request()->input('category') == $cat->id) class="active-f-view" @endif><a href="{{route('rooms') . '?category=' . $cat->id}}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
              </div>
            </div>
            @endif

          @includeIf('frontend.rooms.grid_view')

          @includeIf('frontend.rooms.room_sidebar')
        </div>

      </div>
    </section>
    <!-- All Rooms Section Start -->
  </main>
@endsection
