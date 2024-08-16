@extends('frontend.layout')

@section('pageHeading')
  @if (!is_null($pageHeading))
    {{ $pageHeading->packages_title }}
  @endif
@endsection

@php
  $metaKeys = !empty($seo->meta_keyword_packages) ? $seo->meta_keyword_packages : '';
  $metaDesc = !empty($seo->meta_description_packages) ? $seo->meta_description_packages : '';
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
          @if (!is_null($pageHeading))
            <h1>{{ convertUtf8($pageHeading->packages_title) }}</h1>
          @endif

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            @if (!is_null($pageHeading))
              <li>{{ convertUtf8($pageHeading->packages_title) }}</li>
            @endif
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="packages-area-v1">
      <div class="container">
        <div class="row">
          <div class="col-lg-3">
            <div class="packages-sidebar">
              @if ($websiteInfo->package_category_status == 1)
                <div class="widget search-widget">
                  <h4 class="widget-title">{{ __('Categories') }}</h4>
                  <div class="form_group">
                    <ul class="categories">
                      <li class="@if (empty(request()->input('category'))) active @endif"><a
                          href="{{ route('packages') }}">{{ __('All') }}</a></li>
                      @foreach ($categories as $category)
                        <li class="@if (request()->input('category') == $category->id) active @endif"><a
                            href="{{ route('packages', ['category' => $category->id]) }}">{{ $category->name }}</a></li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              @endif
              <div class="widget search-widget">
                <h4 class="widget-title">{{ __('Search Here') }}</h4>
                <div class="form_group">
                  <input type="text" id="searchInput" placeholder="{{ __('Search By Package Name') }}"
                    value="{{ !empty(request()->input('packageName')) ? request()->input('packageName') : '' }}">
                </div>
              </div>

              <div class="widget location-widget">
                <h4 class="widget-title">{{ __('Location Search') }}</h4>
                <div class="form_group">
                  <input type="text" id="locationSearchInput" placeholder="{{ __('Search By Location') }}"
                    value="{{ !empty(request()->input('locationName')) ? request()->input('locationName') : '' }}">
                </div>
              </div>

              <div class="widget sortby-widget">
                <h4 class="widget-title">{{ __('Days') }}</h4>
                <div class="form_group">
                  <select id="days" class="nice-select">
                    <option selected value="">{{ __('All') }}</option>
                    @for ($i = 0; $i < $maxDays; $i++)
                      <option value="{{ $i + 1 }}"
                        {{ request()->input('daysValue') == $i + 1 ? 'selected' : '' }}>{{ __('Up to') }}
                        {{ $i + 1 }} {{ $i + 1 == 1 ? __('Day') : __('Days') }}</option>
                    @endfor
                  </select>
                </div>
              </div>

              <div class="widget sortby-widget">
                <h4 class="widget-title">{{ __('Persons') }}</h4>
                <div class="form_group">
                  <select id="persons" class="nice-select">
                    <option selected value="">{{ __('All') }}</option>
                    @for ($i = 0; $i < $maxPersons; $i++)
                      <option value="{{ $i + 1 }}"
                        {{ request()->input('personsValue') == $i + 1 ? 'selected' : '' }}>{{ $i + 1 }}
                        {{ $i + 1 == 1 ? __('Person & More') : __('Persons & More') }}</option>
                    @endfor
                  </select>
                </div>
              </div>

              <div class="widget sortby-widget">
                <h4 class="widget-title">{{ __('Sort By') }}</h4>
                <div class="form_group">
                  <select id="sortType" class="nice-select">
                    <option value="new-packages" {{ request()->input('sortValue') == 'new-packages' ? 'selected' : '' }}>
                      {{ __('New Packages') }}</option>
                    <option value="old-packages" {{ request()->input('sortValue') == 'old-packages' ? 'selected' : '' }}>
                      {{ __('Old Packages') }}</option>
                    <option value="price-asc" {{ request()->input('sortValue') == 'price-asc' ? 'selected' : '' }}>
                      {{ __('Price: Ascending') }}</option>
                    <option value="price-desc" {{ request()->input('sortValue') == 'price-desc' ? 'selected' : '' }}>
                      {{ __('Price: Descending') }}</option>
                    <option value="max-persons-asc"
                      {{ request()->input('sortValue') == 'max-persons-asc' ? 'selected' : '' }}>
                      {{ __('Maximum Persons: Ascending') }}</option>
                    <option value="max-persons-desc"
                      {{ request()->input('sortValue') == 'max-persons-desc' ? 'selected' : '' }}>
                      {{ __('Maximum Persons: Descending') }}</option>
                    <option value="days-asc" {{ request()->input('sortValue') == 'days-asc' ? 'selected' : '' }}>
                      {{ __('Number of Days: Ascending') }}</option>
                    <option value="days-desc" {{ request()->input('sortValue') == 'days-desc' ? 'selected' : '' }}>
                      {{ __('Number of Days: Descending') }}</option>
                  </select>
                </div>
              </div>

              <div class="widget price_ranger_widget">
                <h4 class="widget-title">{{ __('Filter By Price') }}</h4>
                <div id="slider-range"></div>
                <label for="amount">{{ __('Price') . ' :' }}</label>
                <input type="text" id="amount" readonly>
              </div>
            </div>
          </div>

          <div class="col-lg-9">
            @if (count($packageInfos) == 0)
              <div class="row text-center">
                <div class="col py-5 bg-light">
                  <h3>{{ __('No Package Found!') }}</h3>
                </div>
              </div>
            @else
              <div class="packages-wrapper">
                @foreach ($packageInfos as $packageInfo)
                  <div class="row package-item mb-30 align-items-center">
                    <figure class="package-img col-md-2 col-xs-12">
                        <a href="{{ route('package_details', [$packageInfo->package_id, $packageInfo->slug]) }}">
                          <img class="lazy" data-src="{{ asset('assets/img/package/' . $packageInfo->featured_img) }}"
                            alt="img">
                        </a>
                    </figure>

                    <div class="package-details col-md-6 col-xs-12 border-right">
                        <h5 class="package-title mb-2">
                            <a href="{{ route('package_details', ['id' => $packageInfo->package_id, 'slug' => $packageInfo->slug]) }}">{{ strlen($packageInfo->title) > 70 ? mb_substr($packageInfo->title, 0, 70, 'utf-8') . '...' : $packageInfo->title }}</a>
                        </h5>
                        <h6 class="vendor mb-3">
                            @if ($packageInfo->vendor_id != null)
                            @php
                                $vendor = App\Models\Vendor::where('id', $packageInfo->vendor_id)->first();
                            @endphp
                            <a href="{{ route('frontend.vendor.details', $vendor->username) }}">{{ __('By') }}
                                {{ $vendor->username }}</a>
                            @else
                            @php
                                $admin = App\Models\Admin::first();
                            @endphp
                            <a href="{{ route('frontend.vendor.details', [$admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                {{ $admin->username }}</a>
                            @endif
                        </h6>
                        <ul class="package-list-group list-unstyled">
                            @if ($packageInfo->pricing_type != 'negotiable')
                            <li><span><i class="fas fa-comment-dollar"></i><strong>{{ __('Package Price') }}:</strong>
                                {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                {{ $packageInfo->package_price }}
                                {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                {{ '(' . __(strtoupper("$packageInfo->pricing_type")) . ')' }}</span></li>
                            @else
                            <li><span><i class="fas fa-comment-dollar"></i><strong>{{ __('Package Price') }}:</strong>
                                {{ __('NEGOTIABLE') }}</span></li>
                            @endif

                            <li><span><i class="fas fa-calendar-alt"></i><strong>{{ __('Number of Days') }}:</strong>
                                {{ $packageInfo->number_of_days }}</span></li>

                            <li><span><i class="fas fa-users"></i><strong>{{ __('Maximum Persons') }}:</strong>
                                {{ $packageInfo->max_persons != null ? $packageInfo->max_persons : '-' }}</span></li>
                        </ul>
                    </div>

                    <div class="col-md-4 col-xs-12 text-center">
                        <h6 class="price">
                            @if ($packageInfo->pricing_type != 'negotiable')
                                {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                {{ $packageInfo->package_price }}
                                {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                {{ '(' . __(strtoupper("$packageInfo->pricing_type")) . ')' }}
                            @else
                                {{ __('NEGOTIABLE') }}
                            @endif
                        </h6>
                        <div class="ratings justify-content-center mb-1">
                            @if ($packageRating->package_rating_status == 1)
                              @php
                                $avgRating = \App\Models\PackageManagement\PackageReview::where('package_id', $packageInfo->package_id)->avg('rating');
                              @endphp
                              <div class="rate">
                                <div class="rating" style="width:{{ $avgRating * 20 }}%"></div>
                              </div>
                            @endif
                        </div>
                        <a href="{{ route('package_details', ['id' => $packageInfo->package_id, 'slug' => $packageInfo->slug]) }}" class="btn-text">
                            {{ __('View More') }}
                        </a>
                    </div>
                  </div>
                @endforeach
              </div>

              <div class="row">
                <div class="col-12">
                  {{ $packageInfos->appends(['packageName' => request()->input('packageName'), 'daysValue' => request()->input('daysValue'), 'personsValue' => request()->input('personsValue'), 'sortValue' => request()->input('sortValue'), 'locationName' => request()->input('locationName'), 'minPrice' => request()->input('minPrice'), 'maxPrice' => request()->input('maxPrice')])->links() }}
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>

    {{-- search form start --}}
    <form class="d-none" action="{{ route('packages') }}" method="GET">
      @if ($websiteInfo->package_category_status == 1)
        <input type="hidden" id="categoryKey" name="category" value="{{ request()->input('category') }}">
      @endif

      <input type="hidden" id="searchKey" name="packageName" value="{{ request()->input('packageName') }}">

      <input type="hidden" id="daysKey" name="daysValue" value="{{ request()->input('daysValue') }}">

      <input type="hidden" id="personsKey" name="personsValue" value="{{ request()->input('personsValue') }}">

      <input type="hidden" id="sortKey" name="sortValue" value="{{ request()->input('sortValue') }}">

      <input type="hidden" id="locationKey" name="locationName" value="{{ request()->input('locationName') }}">

      <input type="hidden" id="minPriceKey" name="minPrice" value="{{ request()->input('minPrice') }}">

      <input type="hidden" id="maxPriceKey" name="maxPrice" value="{{ request()->input('maxPrice') }}">

      <button type="submit" id="submitBtn"></button>
    </form>
    {{-- search form end --}}
  </main>
@endsection

@section('script')
  <script>
    "use strict";
    // get the currency symbol position and currency symbol
    var currency_info = {!! json_encode($currencyInfo) !!};
    var minprice = {!! htmlspecialchars($minPrice) !!};
    var maxprice = {!! htmlspecialchars($maxPrice) !!};
    var priceValues = [{{ !empty(request()->input('minPrice')) ? request()->input('minPrice') : $minPrice }},
      {{ !empty(request()->input('maxPrice')) ? request()->input('maxPrice') : $maxPrice }}
    ];
  </script>
  <script src="{{ asset('assets/js/packages.js') }}"></script>
@endsection
