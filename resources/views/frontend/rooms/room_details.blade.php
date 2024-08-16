@extends('frontend.layout')

@section('pageHeading')
  {{ __('Room Details') }}
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
            <li>{{ __('Room Details') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="room-details-wrapper section-padding">
      @php
        $position = $currencyInfo->base_currency_symbol_position;
        $symbol = $currencyInfo->base_currency_symbol;
      @endphp

      <div class="container">
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

        {{-- show error message for room review --}}
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
          <!-- Room Details Section Start -->
          <div class="col-lg-8">
            <div class="room-details">
              <div class="entry-header">
                <div class="post-thumb position-relative">
                  <div class="post-thumb-slider">
                    @php
                      $sliders = App\Models\RoomManagement\RoomImage::where('room_id', $details->room_id)->get();
                    @endphp

                    <div class="main-slider">
                      @foreach ($sliders as $slider)
                        <div class="single-img">
                          <a href="{{ asset('assets/img/room-gallery/' . $slider->image) }}" class="main-img">
                            <img src="{{ asset('assets/img/room-gallery/' . $slider->image) }}" alt="Image">
                          </a>
                        </div>
                      @endforeach
                    </div>

                    <div class="dots-slider row">
                      @foreach ($sliders as $slider)
                        <div class="single-dots">
                          <img src="{{ asset('assets/img/room-gallery/' . $slider->image) }}" alt="image">
                        </div>
                      @endforeach
                    </div>
                  </div>
                  <div class="price-tag">
                    {{ $position == 'left' ? $symbol : '' }}{{ $details->room->rent }}{{ $position == 'right' ? $symbol : '' }}
                    / {{ __('Night') }}
                  </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                  @if ($websiteInfo->room_category_status == 1 && !empty($details->roomCategory))
                    <div class="room-cat mb-0">
                      <a
                        href="{{ route('rooms', ['category' => $details->roomCategory->id]) }}">{{ $details->roomCategory->name }}</a>
                    </div>
                  @endif

                  @if ($status->room_rating_status == 1)
                    <div class="rate">
                      <div class="rating" style="width:{{ $avgRating * 20 }}%"></div>
                    </div>
                  @endif
                </div>

                <p id="room-id" class="d-none">{{ $details->room_id }}</p>

                <h2 class="entry-title pb-0">{{ convertUtf8($details->title) }}</h2>
                <h6>
                  @if ($details->vendor_id != null)
                    @php
                      $vendor = App\Models\Vendor::where('id', $details->vendor_id)->first();
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

                <ul class="entry-meta list-inline">
                  <li><i class="far fa-bed"></i>{{ $details->room->bed }}
                    {{ $details->room->bed == 1 ? __('Bed') : __('Beds') }}</li>
                  <li><i class="far fa-bath"></i>{{ $details->room->bath }}
                    {{ $details->room->bath == 1 ? __('Bath') : __('Baths') }}</li>
                  @if (!empty($details->room->max_guests))
                    <li><i class="far fa-users"></i>{{ $details->room->max_guests }}
                      {{ $details->room->max_guests == 1 ? __('Guest') : __('Guests') }}</li>
                  @endif
                </ul>
              </div>

              <div class="room-details-tab">
                <div class="row">
                  <div class="col-sm-3">
                    <ul class="nav desc-tab-item" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" href="#desc" role="tab" data-toggle="tab">
                          {{ __('Room Details') }}
                        </a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link" href="#amm" role="tab" data-toggle="tab">
                          {{ __('Amenities') }}
                        </a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link" href="#location" role="tab" data-toggle="tab">
                          {{ __('Contact Info') }}
                        </a>
                      </li>

                      <li class="nav-item {{ $status->room_rating_status == 0 ? 'd-none' : '' }}">
                        <a class="nav-link" href="#reviews" role="tab" data-toggle="tab">
                          {{ __('Reviews') }}
                        </a>
                      </li>
                    </ul>
                  </div>

                  <div class="col-sm-9">
                    <div class="tab-content desc-tab-content">
                      <div role="tabpanel" class="tab-pane fade in active show" id="desc">
                        <h5 class="tab-title">{{ __('Room Details') }}</h5>
                        <div class="entry-content">
                          <div class="summernote-content">
                            {!! $details->description !!}
                          </div>
                        </div>
                      </div>

                      <div role="tabpanel" class="tab-pane fade" id="amm">
                        <h5 class="tab-title">{{ __('Amenities') }}</h5>
                        <div class="ammenities">
                          @foreach ($amms as $key => $amm)
                            <a>{{ $amm }}</a>
                          @endforeach
                        </div>
                      </div>

                      <div role="tabpanel" class="tab-pane fade" id="location">
                        <div class="room-location">
                          <div class="row">
                            @if (!empty($details->address))
                              <div class="col-4">
                                <h6>{{ __('Address') }}</h6>
                                <p>{{ $details->address }}</p>
                              </div>
                            @endif

                            @if (!empty($details->phone))
                              <div class="col-4">
                                <h6>{{ __('Phone') }}</h6>
                                <p>{{ $details->phone }}</p>
                              </div>
                            @endif

                            @if (!empty($details->email))
                              <div class="col-4">
                                <h6>{{ __('Email') }}</h6>
                                <p>{{ $details->email }}</p>
                              </div>
                            @endif
                          </div>
                        </div>

                        @if (!empty($details->latitude) && !empty($details->longitude))
                          <h5 class="tab-title mt-3">{{ __('Google Map') }}</h5>
                          <div>
                            <iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0"
                              marginwidth="0"
                              src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $details->latitude }},%20{{ $details->longitude }}+(My%20Business%20Name)&amp;t=&amp;z=15&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                          </div>
                        @endif
                      </div>

                      <div role="tabpanel" class="tab-pane fade" id="reviews">
                        <div class="comment-area">
                          <h5 class="tab-title">{{ __('Reviews') }}</h5>

                          @if (count($reviews) == 0)
                            <div class="bg-light py-5">
                              <h6 class="text-center">{{ __('This Room Has No Review Yet.') }}</h6>
                            </div>
                          @else
                            <ul class="comment-list">
                              @foreach ($reviews as $review)
                                <li>
                                  @php
                                    $user = $review->roomReviewedByUser()->first();
                                  @endphp

                                  <div class="comment-user">
                                    <img class="lazy"
                                      data-src="{{ !empty($user->image) ? asset('assets/img/users/' . $user->image) : asset('assets/img/user-profile.jpg') }}"
                                      alt="user image">
                                  </div>

                                  <div class="comment-desc">
                                    <h6>{{ $user->first_name . ' ' . $user->last_name }} <span class="comment-date">
                                        {{ date_format($review->created_at, 'd M Y') }}</span></h6>

                                    <p>{{ $review->comment }}</p>

                                    <div class="user-rating">
                                      @for ($i = 1; $i <= $review->rating; $i++)
                                        <i class="fa fa-star"></i>
                                      @endfor
                                    </div>
                                  </div>
                                </li>
                              @endforeach
                            </ul>
                          @endif
                        </div>

                        @guest('web')
                          <h5>{{ __('Please') }} <a
                              href="{{ route('user.login', ['redirectPath' => 'room_details']) }}">{{ __('Login') }}</a>
                            {{ __('To Give Your Review') . '.' }}</h5>
                        @endguest

                        @auth('web')
                          <div class="review-form">
                            <h5 class="tab-title">{{ __('Give Your Review') }}</h5>
                            <form action="{{ route('room.store_review', ['id' => $details->room_id]) }}" method="POST">
                              @csrf
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

                              <input type="hidden" id="ratingId" name="rating">

                              <div class="input-wrap text-area">
                                <textarea placeholder="{{ __('Review') }}" name="comment">{{ old('comment') }}</textarea>
                                <i class="far fa-pencil"></i>
                              </div>

                              <div class="input-wrap">
                                <button type="submit" class="btn btn-block">{{ __('Submit') }}</button>
                              </div>
                            </form>
                          </div>
                        @endauth
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Room Details Section End -->

          <!-- Sidebar Area Start -->
          <div class="col-lg-4">
            <div class="sidebar-wrap">
              <div class="widget booking-widget">
                <h4 class="widget-title">
                  {{ $position == 'left' ? $symbol : '' }}{{ $details->room->rent }}{{ $position == 'right' ? $symbol : '' }}
                  / <span>{{ __('Night') }}</span>
                </h4>

                @if (Auth::guard('web')->check() == false && $status->room_guest_checkout_status == 1)
                  <div class="alert alert-warning">
                    {{ __('You are now booking as a guest. if you want to log in before booking, then please') }} <a
                      href="{{ route('user.login', ['redirectPath' => 'room_details']) }}">{{ __('Click Here') }}</a>
                  </div>
                @endif

                <form action="{{ route('room_booking') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="room_id" value="{{ $details->room_id }}">

                  <div class="mb-2">
                    <div class="input-wrap">
                      <input type="text" placeholder="{{ __('Check In / Out Date') }}" id="date-range"
                        name="dates" value="{{ old('dates') }}" readonly>
                      <i class="far fa-calendar-alt"></i>
                    </div>
                    @error('dates')
                      <p class="ml-2 mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="mb-2">
                    <div class="input-wrap">
                      <input type="text" placeholder="{{ __('Number of Nights') }}" id="night" name="nights"
                        value="{{ old('nights') }}" readonly>
                      <i class="fas fa-moon"></i>
                    </div>
                    <small class="text-primary mt-2 {{ $currentLanguageInfo->direction == 0 ? 'ml-2' : 'mr-2' }} mb-0">
                      {{ __('Number of nights will be calculated based on checkin & checkout date') }}
                    </small>
                    @error('nights')
                      <p class="ml-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="mb-2">
                    <div class="input-wrap">
                      <input type="text" placeholder="{{ __('Number of Guests') }}" name="guests"
                        value="{{ old('guests') }}">
                      <i class="far fa-users"></i>
                    </div>
                    @error('guests')
                      <p class="ml-2 mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="mb-2">
                    <div class="input-wrap">
                      @guest('web')
                        <input type="text" placeholder="{{ __('Full Name') }}" name="customer_name"
                          value="{{ old('customer_name') }}">
                      @endguest

                      @auth('web')
                        @php
                          if (!empty(Auth::guard('web')->user()->first_name) || !empty(Auth::guard('web')->user()->last_name)) {
                              $name = Auth::guard('web')->user()->first_name . ' ' . Auth::guard('web')->user()->last_name;
                          } else {
                              $name = '';
                          }
                        @endphp

                        <input type="text" placeholder="{{ __('Full Name') }}" name="customer_name"
                          value="{{ $name }}">
                      @endauth
                      <i class="far fa-user"></i>
                    </div>
                    @error('customer_name')
                      <p class="ml-2 mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="mb-2">
                    <div class="input-wrap">
                      @guest('web')
                        <input type="text" placeholder="{{ __('Phone') }}" name="customer_phone"
                          value="{{ old('customer_phone') }}">
                      @endguest

                      @auth('web')
                        <input type="text" placeholder="{{ __('Phone') }}" name="customer_phone"
                          value="{{ Auth::guard('web')->user()->contact_number }}">
                      @endauth
                      <i class="far fa-phone"></i>
                    </div>
                    @error('customer_phone')
                      <p class="ml-2 mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="mb-2">
                    <div class="input-wrap">
                      @guest('web')
                        <input type="email" placeholder="{{ __('Email') }}" name="customer_email"
                          value="{{ old('customer_email') }}">
                      @endguest

                      @auth('web')
                        <input type="email" placeholder="{{ __('Email') }}" name="customer_email"
                          value="{{ Auth::guard('web')->user()->email }}">
                      @endauth
                      <i class="far fa-envelope"></i>
                    </div>
                    @error('customer_email')
                      <p class="ml-2 mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                  <div class="mb-2">
                    <div class="input-wrap">
                      <select class="nice-select" name="paymentType" id="payment-gateways">
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
                  </div>

                  <div class="row d-none" id="tab-stripe">
                    <div class="col-12">
                      <div class="field-input">
                        <input type="text" class="card-elements mb-2" name="card_number"
                          placeholder="{{ __('Card Number') }}" autocomplete="off"
                          value="{{ old('card_number') }}">
                      </div>
                      @error('card_number')
                        <p class="ml-2 mb-2 text-danger">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>

                    <div class="col-12">
                      <div class="field-input">
                        <input type="text" class="card-elements mb-2" placeholder="{{ __('CVC Number') }}"
                          name="cvc_number" value="{{ old('cvc_number') }}">
                      </div>
                      @error('cvc_number')
                        <p class="ml-2 mb-2 text-danger">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>

                    <div class="col-12">
                      <div class="field-input">
                        <input type="text" class="card-elements mb-2" placeholder="{{ __('Expiry Month') }}"
                          name="expiry_month" value="{{ old('expiry_month') }}">
                      </div>
                      @error('expiry_month')
                        <p class="ml-2 mb-2 text-danger">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>

                    <div class="col-12 mb-4">
                      <div class="field-input">
                        <input type="text" class="card-elements" placeholder="{{ __('Expiry Year') }}"
                          name="expiry_year" value="{{ old('expiry_year') }}">
                      </div>
                      @error('expiry_year')
                        <p class="ml-2 mt-2 text-danger">{{ convertUtf8($message) }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="d-none my-3 px-2" id="gateway-description"></div>

                  <div class="d-none mb-3 px-2" id="gateway-instruction"></div>

                  <div class="input-wrap d-none mb-4 pl-2" id="gateway-attachment">
                    <input type="file" name="attachment">
                  </div>

                  <div class="mb-2">
                    <div class="input-wrap d-flex">
                      <input type="text" id="coupon-code" placeholder="{{ __('Enter Your Coupon') }}">
                      <button type="button" class="btn filled-btn coupon-btn-padding" onclick="applyCoupon(event)" >
                        {{ __('Apply') }}
                      </button>
                    </div>
                  </div>

                  <div class="price-option-table mt-4">
                    <ul>
                      <li class="single-price-option">
                        <span class="title">{{ __('Subtotal') }} <span
                            class="amount">{{ $position == 'left' ? $symbol : '' }}<span
                              id="subtotal-amount">0.00</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                      </li>

                      <li class="single-price-option">
                        <span class="title">{{ __('Discount') }} <span class="text-success">(<i
                              class="fas fa-minus"></i>)</span> <span
                            class="amount">{{ $position == 'left' ? $symbol : '' }}<span
                              id="discount-amount">0.00</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                      </li>

                      <li class="single-price-option">
                        <span class="title">{{ __('Total') }} <span
                            class="amount">{{ $position == 'left' ? $symbol : '' }}<span
                              id="total-amount">0.00</span>{{ $position == 'right' ? $symbol : '' }}</span></span>
                      </li>
                    </ul>
                  </div>

                  <div class="mt-4">
                    <div class="input-wrap">
                      <button type="submit" class="btn filled-btn btn-block">
                        {{ __('book now') }} <i class="far fa-long-arrow-right"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- Sidebar Area End -->
        </div>
      </div>
    </section>

    <!-- Latest Room Start -->
    <section class="latest-room-d section-bg section-padding">
      <div class="container">
        <!-- Section Title -->
        <div class="section-title text-center">
          <h1>{{ __('Related Rooms') }}</h1>
        </div>

        <div class="row">
          @foreach ($latestRooms as $latestRoom)
            @if (!is_null($latestRoom->room))
              <div class="col-lg-4 col-md-6">
                <!-- Single Room -->
                <div class="single-room">
                  <a class="room-thumb d-block"
                    href="{{ route('room_details', [$latestRoom->room->id, $latestRoom->slug]) }}">
                    <img class="lazy" data-src="{{ asset('assets/img/rooms/' . $latestRoom->room->featured_img) }}"
                      alt="room">
                    <div class="room-price">
                      <p>
                        {{ $position == 'left' ? $symbol : '' }}{{ $latestRoom->room->rent }}{{ $position == 'right' ? $symbol : '' }}
                        / {{ __('Night') }}</p>
                    </div>
                  </a>
                  <div class="room-desc">
                    @if ($websiteInfo->room_category_status == 1)
                      <div class="room-cat">
                        <a class="p-0 d-block"
                          href="{{ route('rooms', ['category' => $latestRoom->roomCategory->id]) }}">{{ $latestRoom->roomCategory->name }}</a>
                      </div>
                    @endif

                    <h4>
                      <a
                        href="{{ route('room_details', ['id' => $latestRoom->room_id, 'slug' => $latestRoom->slug]) }}">{{ convertUtf8($latestRoom->title) }}</a>
                    </h4>
                    <h6>
                      @if ($details->vendor_id != null)
                        @php
                          $vendor = App\Models\Vendor::where('id', $details->vendor_id)->first();
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
                    <p>{{ $latestRoom->summary }}</p>
                    <ul class="room-info">
                      <li><i class="far fa-bed"></i>{{ $latestRoom->room->bed }}
                        {{ $latestRoom->room->bed == 1 ? __('Bed') : __('Beds') }}</li>
                      <li><i class="far fa-bath"></i>{{ $latestRoom->room->bath }}
                        {{ $latestRoom->room->bath == 1 ? __('Bath') : __('Baths') }}</li>
                      @if (!empty($latestRoom->room->max_guests))
                        <li><i class="far fa-users"></i>{{ $latestRoom->room->max_guests }}
                          {{ $latestRoom->room->max_guests == 1 ? __('Guest') : __('Guests') }}</li>
                      @endif
                    </ul>
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        </div>
      </div>
    </section>
    <!-- Latest Room End -->
  </main>
@endsection

@section('script')
  <script>
    'use strict';

    // assign php value to js variable
    var bookingDates = {!! json_encode($bookingDates) !!};
    var offlineGateways = {!! json_encode($offlineGateways) !!};
    var roomRentPerNight = '{{ $details->rent }}';
  </script>

  <script src="{{ asset('assets/js/room-details.js') }}"></script>
@endsection
