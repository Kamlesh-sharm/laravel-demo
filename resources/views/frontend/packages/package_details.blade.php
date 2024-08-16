@extends('frontend.layout')

@section('pageHeading')
  {{ __('Package Details') }}
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
          <h1>{{ strlen($details->title) > 30 ? mb_substr($details->title, 0, 30, 'utf-8') . '...' : $details->title }}
          </h1>

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>

            <li>{{ __('Package Details') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="packages-details-area">
      <div class="container details-wrapper">
        {{-- show error message for attachment --}}
        @error('attachment')
          <div class="row">
            <div class="col">
              <div class="alert alert-danger alert-block">
                <strong>{{ $message }}</strong>
                <button type="button" class="close" data-dismiss="alert">×</button>
              </div>
            </div>
          </div>
        @enderror

        {{-- show error message for package review --}}
        @error('rating')
          <div class="row">
            <div class="col">
              <div class="alert alert-danger alert-block">
                <strong>{{ $message }}</strong>
                <button type="button" class="close" data-dismiss="alert">×</button>
              </div>
            </div>
          </div>
        @enderror

        <div class="row">
          <div class="col-lg-8">
            <div class="packages-details-wrapper">

              <div class="gallery-wrap box-wrap">
                <div class="packages-big-slider">
                  @php
                    $sliders = App\Models\PackageManagement\PackageImage::where('package_id', $details->package_id)->get();
                  @endphp

                  @foreach ($sliders as $slider)
                    <div class="single-item">
                      <a href="{{ asset('assets/img/package-gallery/' . $slider->image) }}" class="gallery-single">
                        <img src="{{ asset('assets/img/package-gallery/' . $slider->image) }}" alt="image">
                      </a>
                    </div>
                  @endforeach
                </div>

                <div class="packages-thumb-slider">
                  @foreach ($sliders as $thumbImg)
                    <div class="single-item">
                      <img src="{{ asset('assets/img/package-gallery/' . $thumbImg->image) }}" alt="image">
                    </div>
                  @endforeach
                </div>
              </div>

              <p id="package-id" class="d-none">{{ $details->package_id }}</p>

              <div class="discription-box box-wrap">
                <h4 class="title">{{ convertUtf8($details->title) }}</h4>
                <h6>
                  @if ($details->package->vendor_id != null)
                    @php
                      $vendor = App\Models\Vendor::where('id', $details->package->vendor_id)->first();
                    @endphp
                    <a href="{{ route('frontend.vendor.details', $vendor->username) }}">{{ __('By') }}
                      {{ $vendor->username }}</a>
                  @else
                    @php
                      $admin = App\Models\Admin::first();
                    @endphp
                    <a href="javascript:void(0)">{{ __('By') }}
                      {{ $admin->username }}</a>
                  @endif
                </h6>
                <div class="summernote-content">
                  {!! $details->description !!}
                </div>
              </div>

              @if (count($plans) > 0)
                <div class="schedule-wrapp box-wrap">
                  @if ($details->package->plan_type == 'daywise')
                    <h4 class="title">{{ __('Detailed Day-wise Itinerary') }}</h4>

                    @foreach ($plans as $plan)
                      <div class="single-schedule">
                        <div class="icon">
                          <i class="far fa-calendar-alt"></i>
                        </div>
                        <div class="content">
                          <h4>{{ __('Day') . '-' . $plan->day_number . ' : ' . $plan->title }}</h4>
                          <div class="summernote-content">
                            {!! $plan->plan !!}
                          </div>
                        </div>
                      </div>
                    @endforeach
                  @elseif ($details->package->plan_type == 'timewise')
                    <h4 class="title">{{ __('Detailed Time-wise Itinerary') }}</h4>
                    @foreach ($plans as $plan)
                      <div class="single-schedule">
                        <div class="icon">
                          <i class="far fa-clock"></i>
                        </div>
                        <div class="content">
                          <h4>{{ $plan->start_time . ' - ' . $plan->end_time . ' : ' . $plan->title }}</h4>
                          <div class="summernote-content">
                            {!! $plan->plan !!}
                          </div>
                        </div>
                      </div>
                    @endforeach
                  @endif
                </div>
              @endif

              @if (count($locations) > 0)
                <div class="places-box box-wrap">
                  <h4 class="title">{{ __('Places Will Be Covered') }}</h4>
                  @foreach ($locations as $location)
                    <a href="#" data-toggle="modal" title="{{ __('Click here to see in map') }}"
                      data-target="#locationModal{{ $location->id }}">{{ $location->name }}</a>

                    <!-- Location Modal -->
                    @if (!empty($location->latitude) && !empty($location->longitude))
                      <div class="modal fade" id="locationModal{{ $location->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLongTitle">
                                {{ $location->name . ' ' . __('on Map') }}</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body p-0">
                              <iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0"
                                marginwidth="0"
                                src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $location->latitude }},%20{{ $location->longitude }}+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              @endif

              @if ($details->package->pricing_type != 'negotiable')
                <div class="review-area box-wrap {{ $status->package_rating_status == 0 ? 'd-none' : '' }}">
                  <h4 class="title">{{ __('Client’s Reviews') }}</h4>

                  @if (count($reviews) == 0)
                    <div class="py-4 bg-light mb-2">
                      <h5 class="text-center mb-3">{{ __('This Package Has No Review Yet.') }}</h5>
                    </div>
                  @else
                    <ul class="review-list">
                      @foreach ($reviews as $review)
                        <li>
                          @php
                            $user = $review->packageReviewedByUser()->first();
                          @endphp

                          <div class="review-user">
                            <img class="lazy"
                              data-src="{{ !empty($user->image) ? asset('assets/img/users/' . $user->image) : asset('assets/img/user-profile.jpg') }}"
                              alt="user image">
                          </div>

                          <div class="review-desc">
                            <div class="rate mb-2">
                              <div class="rating" style="width:{{ $review->rating * 20 }}%"></div>
                            </div>

                            <h6>{{ $user->first_name . ' ' . $user->last_name }} <span class="review-date">
                                {{ date_format($review->updated_at, 'd M Y') }}</span></h6>

                            <p>{{ $review->comment }}</p>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  @endif
                </div>
              @endif

              @if ($status->package_rating_status == 1)
                @guest('web')
                  <h4><a href="{{ route('user.login', ['redirectPath' => 'package_details']) }}">{{ __('Login') }}</a>
                    {{ __('To Give Your Review') }}.</h4>
                @endguest

                @auth('web')
                  @if ($details->package->pricing_type != 'negotiable')
                    <div class="review-form box-wrap">
                      <h4 class="title">{{ __('Give Your Review') }}</h4>
                      <form action="{{ route('package.store_review', ['id' => $details->package_id]) }}" method="POST">
                        @csrf
                        <div class="row">
                          <div class="col-12">
                            <div class="mb-25">
                              <div class="review-content">
                                <ul class="review-value review-1">
                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="1">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>
                                </ul>

                                <ul class="review-value review-2">
                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="2">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="2">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>
                                </ul>

                                <ul class="review-value review-3">
                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="3">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="3">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="3">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>
                                </ul>

                                <ul class="review-value review-4">
                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="4">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="4">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="4">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="4">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>
                                </ul>

                                <ul class="review-value review-5">
                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="5">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="5">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="5">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="5">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>

                                  <li>
                                    <a class="cursor-pointer" data-ratingVal="5">
                                      <i class="far fa-star"></i>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>

                          <input type="hidden" id="ratingId" name="rating">

                          <div class="col-12">
                            <div class="input-wrap text-area">
                              <textarea placeholder="{{ __('Review') }}" name="comment">{{ old('comment') }}</textarea>
                              <i class="far fa-pencil"></i>
                            </div>
                          </div>

                          <div class="col-12">
                            <button type="submit" class="btn filled-btn">{{ __('Submit') }}</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  @endif
                @endauth
              @endif
            </div>
          </div>

          @php
            $position = $currencyInfo->base_currency_symbol_position;
            $symbol = $currencyInfo->base_currency_symbol;
          @endphp

          <div class="col-lg-4">
            <div class="packages-sidebar">
              <div class="widget information-widget">
                <h4 class="widget-title">{{ __('Information') }}</h4>
                <ul class="list">
                  @if ($details->package->pricing_type == 'negotiable')
                    <li><strong>{{ __('Price') }}</strong>: {{ __(strtoupper($details->package->pricing_type)) }}
                    </li>
                  @else
                    <li><strong>{{ __('Price') }}</strong> :
                      {{ $position == 'left' ? $symbol : '' }}{{ $details->package->package_price }}{{ $position == 'right' ? $symbol : '' }}
                      {{ '(' . __(strtoupper($details->package->pricing_type)) . ')' }}</li>
                  @endif
                  <li><strong>{{ __('Number of Days') }}</strong> : {{ $details->package->number_of_days }}</li>
                  <li><strong>{{ __('Maximum Persons') }}</strong> :
                    {{ $details->package->max_persons != null ? $details->package->max_persons : '-' }}</li>
                </ul>
                @if ($packageRating->package_rating_status == 1)
                  <div class="rate">
                    <div class="rating" style="width:{{ $avgRating * 20 }}%"></div>
                  </div>
                @endif
              </div>

              <div class="widget booking-widget {{ $details->package->pricing_type == 'negotiable' ? 'd-none' : '' }}">
                <h4 class="widget-title">{{ __('Book Package') }}</h4>

                @if (Auth::guard('web')->check() == false && $status->package_guest_checkout_status == 1)
                  <div class="alert alert-warning">
                    {{ __('You are now booking as a guest. if you want to log in before booking, then please') }} <a
                      href="{{ route('user.login', ['redirectPath' => 'package_details']) }}">{{ __('Click Here') }}</a>
                  </div>
                @endif

                <form action="{{ route('package_booking') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="package_id" value="{{ $details->package_id }}">

                  <div class="form_group">
                    @guest('web')
                      <input type="text" placeholder="{{ __('Full Name') }}" name="customer_name"
                        value="{{ old('customer_name') }}">
                    @endguest

                    @auth('web')
                      @php
                        if (!empty(Auth::guard('web')->user()->first_name) || !empty(Auth::guard('web')->user()->last_name)) {
                            $fname = Auth::guard('web')->user()->first_name . ' ' . Auth::guard('web')->user()->last_name;
                        } else {
                            $fname = '';
                        }
                      @endphp
                      <input type="text" placeholder="{{ __('Full Name') }}" name="customer_name"
                        value="{{ $fname }}">
                    @endauth
                    @error('customer_name')
                      <p class="mt-2 ml-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="form_group">
                    @guest('web')
                      <input type="text" placeholder="{{ __('Phone Number') }}" name="customer_phone"
                        value="{{ old('customer_phone') }}">
                    @endguest

                    @auth('web')
                      <input type="text" placeholder="{{ __('Phone Number') }}" name="customer_phone"
                        value="{{ Auth::guard('web')->user()->contact_number }}">
                    @endauth
                    @error('customer_phone')
                      <p class="mt-2 ml-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="form_group">
                    @guest('web')
                      <input type="email" placeholder="{{ __('Email Address') }}" name="customer_email"
                        value="{{ old('customer_email') }}">
                    @endguest

                    @auth('web')
                      <input type="email" placeholder="{{ __('Email Address') }}" name="customer_email"
                        value="{{ Auth::guard('web')->user()->email }}">
                    @endauth
                    @error('customer_email')
                      <p class="mt-2 ml-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="form_group">
                    <input type="text" placeholder="{{ __('Number of Visitors') }}" name="visitors"
                      value="{{ old('visitors') }}">
                    @error('visitors')
                      <p class="mt-2 ml-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="form_group h-50">
                    <select name="paymentType" id="payment-gateways" class="nice-select">
                      <option selected value="none">
                        {{ __('Select Payment Gateway') }}
                      </option>
                      @foreach ($onlineGateways as $onlineGateway)
                        <option value="{{ $onlineGateway->keyword }}">
                          {{ __($onlineGateway->name) }}
                        </option>
                      @endforeach

                      @if (!empty($offlineGateways))
                        @foreach ($offlineGateways as $offlineGateway)
                          <option value="{{ $offlineGateway['id'] }}">
                            {{ __($offlineGateway['name']) }}
                          </option>
                        @endforeach
                      @endif
                    </select>
                  </div>

                  <div class="row d-none" id="tab-stripe">
                    <div class="col-12 mb-3">
                      <div class="field-input">
                        <input type="text" class="card-elements" name="card_number"
                          placeholder="{{ __('Card Number') }}" autocomplete="off"
                          value="{{ old('card_number') }}" />
                      </div>
                      @error('card_number')
                        <p class="ml-2 mt-2 text-danger">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                    <div class="col-12 mb-3">
                      <div class="field-input">
                        <input type="text" class="card-elements" placeholder="{{ __('CVC Number') }}"
                          name="cvc_number" value="{{ old('cvc_number') }}">
                      </div>
                      @error('cvc_number')
                        <p class="ml-2 mt-2 text-danger">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                    <div class="col-12 mb-3">
                      <div class="field-input">
                        <input type="text" class="card-elements" placeholder="{{ __('Expiry Month') }}"
                          name="expiry_month" value="{{ old('expiry_month') }}">
                      </div>
                      @error('expiry_month')
                        <p class="ml-2 mt-2 text-danger">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                    <div class="col-12 mb-4">
                      <div class="field-input">
                        <input type="text" class="card-elements mb-0" placeholder="{{ __('Expiry Year') }}"
                          name="expiry_year" value="{{ old('expiry_year') }}">
                      </div>
                      @error('expiry_year')
                        <p class="ml-2 mt-2 text-danger">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="d-none my-3 px-2" id="gateway-description"></div>

                  <div class="d-none mb-3 px-2" id="gateway-instruction"></div>

                  <div class="d-none mb-4 pl-2" id="gateway-attachment">
                    <input type="file" name="attachment">
                  </div>

                  <div class="mb-2">
                    <div class="d-flex">
                      <input type="text" id="coupon-code" placeholder="{{ __('Enter Your Coupon') }}">
                      <button type="button" class="btn filled-btn coupon-btn-padding" onclick="applyCoupon(event)" >
                        {{ __('Apply') }}
                      </button>
                    </div>
                  </div>

                  <div class="price-option-table mt-4">
                    <ul>
                      <input type="hidden" id="base-price" value="{{ $details->package->package_price }}">
                      <li class="single-price-option">
                        <span class="title">{{ __('Subtotal') }} <span
                            class="amount">{{ $position == 'left' ? $symbol : '' }}<span
                              id="subtotal-amount">{{ $details->package->package_price }}</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                      </li>

                      <li class="single-price-option">
                        <span class="title">{{ __('Discount') }} <span class="text-success">(<i
                              class="fas fa-minus"></i>)</span>
                          <span class="amount">{{ $position == 'left' ? $symbol : '' }}<span
                              id="discount-amount">0.00</span>{{ $position == 'right' ? $symbol : '' }}</span>
                        </span>
                      </li>

                      <li class="single-price-option">
                        <span class="title">{{ __('Total') }} <span
                            class="amount">{{ $position == 'left' ? $symbol : '' }}<span
                              id="total-amount">{{ $details->package->package_price }}</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                      </li>
                    </ul>
                  </div>

                  <div class="form_group mt-4">
                    <button class="btn filled-btn d-inline-block">{{ __('Book Now') }}</button>
                  </div>
                </form>
              </div>

              @if (!is_null($details->package->email) || !is_null($details->package->phone))
                <div class="widget information-widget">
                  <h4 class="widget-title">{{ __('Help & Support') }}</h4>
                  <ul class="list">
                    @if (!is_null($details->package->phone))
                      <li><strong>{{ __('Phone') }}</strong>: {{ $details->package->phone }}</li>
                    @endif
                    @if (!is_null($details->package->email))
                      <li><strong>{{ __('Email') }}</strong>: {{ $details->package->email }}</li>
                    @endif
                  </ul>
                </div>
              @endif

              <div class="widget share-widget">
                <h4 class="widget-title">{{ __('Share This Package') }}</h4>
                <ul class="social-icons">
                  <li><a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                      class="facebook"><i class="fab fa-facebook-f"></i></a></li>
                  <li><a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"
                      class="twitter"><i class="fab fa-twitter"></i></a></li>
                  <li><a
                      href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ convertUtf8($details->title) }}"
                      class="linkedin"><i class="fab fa-linkedin-in"></i></a></li>
                  <li><a href="//plus.google.com/share?url={{ urlencode(url()->current()) }}" class="google"><i
                        class="fab fa-google"></i></a></li>
                </ul>
              </div>

              <div class="widget latest-package-widget">
                <h4 class="widget-title">{{ __('Related Packages') }}</h4>

                @foreach ($latestPackages as $latestPackage)
                  <div class="packages-item">
                    <div class="post-thumbnail">
                      <img class="lazy"
                        data-src="{{ asset('assets/img/package/' . $latestPackage->package->featured_img) }}"
                        alt="image">
                    </div>

                    <div class="entry-content">
                      <h3 class="title"><a
                          href="#">{{ strlen($latestPackage->title) > 30 ? mb_substr($latestPackage->title, 0, 30, 'UTF-8') . '...' : $latestPackage->title }}</a>
                      </h3>
                      <h6>
                        @if ($latestPackage->package->vendor_id != null)
                          @php
                            $vendor = App\Models\Vendor::where('id', $latestPackage->package->vendor_id)->first();
                          @endphp
                          <a href="{{ route('frontend.vendor.details', $vendor->username) }}">{{ __('By') }}
                            {{ $vendor->username }}</a>
                        @else
                          @php
                            $admin = App\Models\Admin::first();
                          @endphp
                          <a href="javascript:void(0)">{{ __('By') }}
                            {{ $admin->username }}</a>
                        @endif
                      </h6>
                      <p>
                        {{ strlen(strip_tags($latestPackage->description)) > 50 ? substr(strip_tags($latestPackage->description), 0, 50) . '...' : strip_tags($latestPackage->description) }}
                      </p>
                      <a href="{{ route('package_details', ['id' => $latestPackage->package_id, 'slug' => $latestPackage->slug]) }}"
                        class="btn">{{ __('view package') }}</a>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection

@section('script')
  <script>
    "use strict";
    var offlineGateways = {!! json_encode($offlineGateways) !!};
    var pricingType = '{{ $details->package->pricing_type }}';
    var initialPrice = '{{ $details->package->package_price }}';
  </script>

  <script src="{{ asset('assets/js/package-details.js') }}"></script>
@endsection
