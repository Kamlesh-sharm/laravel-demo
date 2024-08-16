@extends('frontend.layout')

@section('pageHeading')
  {{ __('Vendors') }}
@endsection

@php
  $metaKeys = !empty($seo->meta_keyword_vendors) ? $seo->meta_keyword_vendors : '';
  $metaDesc = !empty($seo->meta_description_vendors) ? $seo->meta_description_vendors : '';
@endphp

@section('meta-keywords', "$metaKeys")
@section('meta-description', "$metaDesc")


@section('content')
  <!-- Main Wrap start -->
  <main>
    <!-- Breadcrumb section -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center"
      style="background-image: url({{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }});">
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ __('All Vendors') }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Vendors') }}</li>
          </ul>
        </div>
      </div>
      <h1 class="big-text">
        {{ __('Vendors') }}
      </h1>
    </section>
    <!-- Breadcrumb section End-->

    <!-- Author-single-area start -->
    <div class="author-area section-bg section-padding">
      <div class="container">
        <div class="authors-search-filter primary-bg mb-30">
          <form action="{{ route('frontend.vendors') }}" method="GET">
            <div class="search-filter-form">
              <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                  <div class="input-wrap mb-20">
                    <input type="text" placeholder="{{ __('Username') }}" name="uo_name"
                      value="{{ request()->input('uo_name') }}">
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                  <div class="input-wrap mb-20">
                    <input type="text" value="{{ request()->input('location') }}" placeholder="{{ __('Location') }}"
                      name="location">
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                  <div class="input-wrap mb-20">
                    <select name="rating">
                      <option value="">{{ __('Show All Rating') }}</option>
                      <option {{ request()->input('rating') == 5 ? 'selected' : '' }} value="5">
                        {{ __('5 Star Rating') }}</option>
                      <option {{ request()->input('rating') == 4 ? 'selected' : '' }} value="4">
                        {{ __('4 Star And Higher') }}</option>
                      <option {{ request()->input('rating') == 3 ? 'selected' : '' }} value="3">
                        {{ __('3 Star And Higher') }}</option>
                      <option {{ request()->input('rating') == 2 ? 'selected' : '' }} value="2">
                        {{ __('2 Star And Higher') }}</option>
                      <option {{ request()->input('rating') == 1 ? 'selected' : '' }} value="1">
                        {{ __('1 Star And Higher') }}</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                  <div class="input-wrap mb-20">
                    <button type="submit" class="btn filled-btn btn-block btn-black">
                      {{ __('Search Now') }} <i class="far fa-long-arrow-right"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="product-filter">
              <div class="row jus justify-content-sm-between align-items-center">
                <div class="col-sm-6">
                  <h6 class="mb-20">{{ __('Total vendors showing') }}: {{ count($vendors) }}</h6>
                </div>
              </div>
            </div>
            @if (count($vendors) > 0)
              <div class="row">
                @foreach ($vendors as $item)
                  <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-center border-0 p-3 mb-30">
                      <figure class="card-img mx-auto mb-2">
                        <a href="{{ route('frontend.vendor.details', $item->username) }}" target="_self"
                          title="kreativDev">
                          @if ($item->photo != null)
                            <img class="rounded-lg" src="{{ asset('assets/admin/img/vendor-photo/' . $item->photo) }}"
                              alt="Author">
                          @else
                            <img class="rounded-lg" src="{{ asset('assets/img/blank_user.jpg') }}" alt="Author">
                          @endif
                        </a>
                      </figure>
                      <div class="card-content">
                        <h4 class="card-title mb-0"><a href="{{ route('frontend.vendor.details', $item->username) }}"
                            target="_self" title="{{ $item->organization_name }}">{{ $item->organization_name }}</a>
                        </h4>

                        <h6 class="card-title mb-0"><a href="{{ route('frontend.vendor.details', $item->username) }}"
                            target="_self" title="{{ $item->username }}">{{ $item->username }}</a></h6>

                        @php
                          $rooms = $item
                              ->rooms()
                              ->where('status', 1)
                              ->get();
                          $packages = $item->packages()->get();
                          $roomIds = [];
                          foreach ($rooms as $room) {
                              if (!in_array($room->id, $roomIds)) {
                                  array_push($roomIds, $room->id);
                              }
                          }
                          $room_review_avg = App\Models\RoomManagement\RoomReview::whereIn('room_id', $roomIds)->avg('rating');
                          
                          $packageIds = [];
                          foreach ($packages as $package) {
                              if (!in_array($package->id, $packageIds)) {
                                  array_push($packageIds, $package->id);
                              }
                          }
                          
                          $package_review_avg = App\Models\PackageManagement\PackageReview::whereIn('package_id', $packageIds)->avg('rating');
                          if ($room_review_avg == null) {
                              $vendor_avg_rating = ($room_review_avg + $package_review_avg) / 1;
                          } elseif ($package_review_avg == null) {
                              $vendor_avg_rating = ($room_review_avg + $package_review_avg) / 1;
                          } else {
                              $vendor_avg_rating = ($room_review_avg + $package_review_avg) / 2;
                          }
                        @endphp
                        <div class="rate">
                          <div class="rating" style="width:{{ $vendor_avg_rating * 20 }}%"></div>
                        </div>

                        <div class="mb-1 font-sm">
                          <span>
                            {{ $item->rooms()->where('status', 1)->get()->count() }}
                            @if ($item->rooms()->where('status', 1)->get()->count() <= 1)
                              {{ __('Room') }}
                            @else
                              {{ __('Rooms') }}
                            @endif
                          </span>
                          <br>
                          <span>
                            {{ $item->packages()->get()->count() }}
                            @if ($item->packages()->get()->count() <= 1)
                              {{ __('Package') }}
                            @else
                              {{ __('Packages') }}
                            @endif
                          </span>
                        </div>
                        <a href="{{ route('frontend.vendor.details', $item->username) }}" target="_self"
                          title="{{ $item->username }}" class="btn-text"> {{ __('View') }}
                        </a>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
              @if (count($vendors) >= 8)
                <div class="pagination-wrap">
                  {{ $vendors->links() }}
                </div>
              @endif
            @else
              <h2 class="text-center mb-30">{{ __('No Vendor Found') . '!' }}</h2>
            @endif
          </div>
        </div>
      </div>
    </div>
    <!-- Author-single-area start -->
  </main>
  <!-- Main Wrap end -->
@endsection
