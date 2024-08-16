@extends('frontend.layout')

@section('pageHeading')
  {{ __('Vendors') }}
@endsection


@section('content')
  <!-- Main Wrap start -->
  <main>
    <!-- Breadcrumb section -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center"
      style="background-image: url({{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }});">
      <div class="container">
        <div class="breadcrumb-content text-center">
          @php
            if (request()->input('admin') == 'true') {
                $vendor_id = null;
            } else {
                $vendor_id = $vendor->id;
            }
          @endphp
          <h1>{{ $vendor->username }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ $vendor->organization_name != null ? $vendor->organization_name : $vendor->username }}</li>
          </ul>
        </div>
      </div>
      <h1 class="big-text">
        {{ $vendor->organization_name != null ? $vendor->organization_name : $vendor->username }}
      </h1>
    </section>
    <!-- Breadcrumb section End-->
    <!-- Author-single-area start -->
    <div class="author-area author-details section-bg section-padding">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <h3 class="mb-20">{{ __('All Listing') }}</h3>
            <div class="tabs-navigation mb-30">
              <ul class="nav nav-tabs">
                <li class="nav-item dropdown">
                  <button class="nav-link active" type="button" data-toggle="tab" data-target="#hotel"
                    aria-selected="true">{{ __('Rooms') }}</button>
                </li>
                <li class="nav-item dropdown">
                  <button class="nav-link " type="button" data-toggle="tab" data-target="#package"
                    aria-selected="true">{{ __('Packages') }}</button>
                </li>
              </ul>
            </div>
            <div class="tab-content mb-55">
              <div class="tab-pane fade show active" id="hotel">
                <div class="tabs-navigation-2 mb-30">
                  <ul class="nav nav-tabs">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab"
                        href="#hotelAll">{{ __('All') }}</a>
                    </li>
                    {{-- hotel categories start --}}
                    @foreach ($room_categories as $room_category)
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab{{ $room_category->id }}" data-toggle="tab"
                          href="#hotel{{ $room_category->id }}">{{ $room_category->name }}</a>
                      </li>
                    @endforeach
                    {{-- hotel categories end --}}
                  </ul>
                </div>
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="hotelAll">
                    @if (count($all_rooms) > 0)
                      <div class="row justify-content-between">
                        @foreach ($all_rooms as $item)
                          <div class="col-sm-6">
                            <!-- Single Room -->
                            <div class="single-room mb-30">
                              <div class="room-thumb d-block">
                                <a
                                  href="{{ route('room_details', ['id' => $item->id, 'slug' => $item->room_content->slug]) }}">
                                  <img src="{{ asset('assets/img/rooms/' . $item->featured_img) }}" alt="Room">
                                </a>
                                <div class="room-price">
                                  <p>
                                    {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                    {{ $item->rent }}
                                    {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                  </p>
                                </div>
                              </div>
                              <div class="room-desc">
                                <div class="room-cat">
                                  @php
                                    $category = $item->room_content
                                        ->roomCategory()
                                        ->select('name')
                                        ->first();
                                  @endphp
                                  @if ($category)
                                    <p>{{ $category->name }}</p>
                                  @endif
                                </div>
                                <h4><a
                                    href="{{ route('room_details', ['id' => $item->id, 'slug' => $item->room_content->slug]) }}">
                                    {{ Str::limit($item->room_content->title, 100, '...') }}
                                  </a>
                                </h4>
                                <p>
                                  {{ Str::limit($item->room_content->summary, 100, '...') }}
                                </p>
                                <ul class="room-info list-inline">
                                  <li><i class="far fa-bed"></i>{{ $item->bed }}
                                    {{ $item->bed == 1 ? __('Bed') : __('Beds') }}</li>
                                  <li><i class="far fa-bath"></i>{{ $item->bath }}
                                    {{ $item->bath == 1 ? __('Bath') : __('Baths') }}</li>
                                  <li><i class="far fa-users"></i>{{ $item->max_guests }}
                                    {{ $item->max_guests == 1 ? __('Guest') : __('Guests') }}</li>
                                </ul>
                                @if ($roomRating->room_rating_status == 1)
                                  @php
                                    $avgRating = \App\Models\RoomManagement\RoomReview::where('room_id', $item->id)->avg('rating');
                                  @endphp
                                  <div class="rate">
                                    <div class="rating" style="width:{{ $avgRating * 20 }}%"></div>
                                  </div>
                                @endif
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @else
                      <h4 class="text-center">{{ __('No Room Found') }} </h4>
                    @endif
                  </div>

                  {{-- start foreach --}}
                  @foreach ($room_categories as $room_category)
                    @php
                      $category_id = $room_category->id;
                      $c_rooms = DB::table('rooms')
                          ->join('room_contents', 'rooms.id', 'room_contents.room_id')
                          ->where('rooms.vendor_id', $vendor_id)
                          ->where('room_contents.room_category_id', $room_category->id)
                          ->where('room_contents.language_id', $language_id)
                          ->where('rooms.status', 1)
                          ->select('rooms.*', 'room_contents.room_category_id', 'room_contents.title', 'room_contents.slug', 'room_contents.summary')
                          ->get();
                    @endphp

                    <div class="tab-pane fade" id="hotel{{ $room_category->id }}">
                      @if (count($c_rooms) > 0)
                        <div class="row justify-content-between">
                          @foreach ($c_rooms as $c_room)
                            <div class="col-sm-6">
                              <!-- Single Room -->
                              <div class="single-room mb-30">
                                <div class="room-thumb d-block">
                                  <a
                                    href="{{ route('room_details', ['id' => $item->id, 'slug' => $item->room_content->slug]) }}">
                                    <img src="{{ asset('assets/img/rooms/' . $c_room->featured_img) }}" alt="Room" />
                                  </a>

                                  <div class="room-price">
                                    <p>
                                      {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                      {{ $c_room->rent }}
                                      {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                    </p>
                                  </div>
                                </div>
                                <div class="room-desc">
                                  <div class="room-cat">
                                    @php
                                      $category = $item->room_content
                                          ->roomCategory()
                                          ->select('name')
                                          ->first();
                                    @endphp
                                    @if ($category)
                                      <p>{{ $category->name }}</p>
                                    @endif
                                  </div>
                                  <h4><a
                                      href="{{ route('room_details', ['id' => $c_room->id, 'slug' => $c_room->slug]) }}">
                                      {{ Str::limit($c_room->title, 100, '...') }}
                                    </a>
                                  </h4>
                                  <p>
                                    {{ Str::limit($c_room->summary, 100, '...') }}
                                  </p>
                                  <ul class="room-info list-inline">
                                    <li><i class="far fa-bed"></i>{{ $c_room->bed }}
                                      {{ $c_room->bed == 1 ? __('Bed') : __('Beds') }}</li>
                                    <li><i class="far fa-bath"></i>{{ $c_room->bath }}
                                      {{ $c_room->bath == 1 ? __('Bath') : __('Baths') }}</li>
                                    <li><i class="far fa-users"></i>{{ $c_room->max_guests }}
                                      {{ $c_room->max_guests == 1 ? __('Guest') : __('Guests') }}</li>
                                  </ul>
                                  @if ($roomRating->room_rating_status == 1)
                                    @php
                                      $avgRating = \App\Models\RoomManagement\RoomReview::where('room_id', $c_room->id)->avg('rating');
                                    @endphp
                                    <div class="rate">
                                      <div class="rating" style="width:{{ $avgRating * 20 }}%"></div>
                                    </div>
                                  @endif
                                </div>
                              </div>
                            </div>
                          @endforeach
                        </div>
                      @else
                        <h4 class="text-center">{{ __('No Room Found') }} </h4>
                      @endif
                    </div>
                  @endforeach
                  {{-- end hotel foreach --}}
                </div>
              </div>
              <div class="tab-pane fade " id="package">
                <div class="tabs-navigation-2 mb-30">
                  <ul class="nav nav-tabs">
                    <li class="nav-item">
                      <a class="nav-link active" id="home-tab" data-toggle="tab"
                        href="#packageAll">{{ __('All') }}</a>
                    </li>
                    @foreach ($package_categories as $package_category)
                      <li class="nav-item">
                        <a class="nav-link" id="home-tab{{ $package_category->id }}" data-toggle="tab"
                          href="#package{{ $package_category->id }}">{{ $package_category->name }}</a>
                      </li>
                    @endforeach
                  </ul>
                </div>
                <div class="tab-content">

                  <div class="tab-pane fade show active" id="packageAll">
                    @if (count($all_packages) > 0)
                      @foreach ($all_packages as $item)
                        <div class="row package-item mb-30 align-items-center">
                          <figure class="package-img col-md-2 col-xs-12">
                            <a href="{{ route('package_details', [$item->id, $item->package_content->slug]) }}">
                              <img src="{{ asset('assets/img/package/' . $item->featured_img) }}" alt="package">
                            </a>
                          </figure>
                          <div class="package-details col-md-6 col-xs-12 border-right">
                            <h4 class="package-title mb-2"><a
                                href="{{ route('package_details', [$item->id, $item->package_content->slug]) }}">
                                {{ Str::limit($item->package_content->title, 70, '...') }}
                              </a>
                            </h4>
                            <ul class="package-list-group list-unstyled">
                              @if ($item->pricing_type != 'negotiable')
                                <li><span><i
                                      class="fas fa-comment-dollar"></i><strong>{{ __('Package Price') }}:</strong>
                                    {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                    {{ $item->package_price }}
                                    {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                    {{ '(' . __(strtoupper("$item->pricing_type")) . ')' }}</span></li>
                              @else
                                <li><span><i
                                      class="fas fa-comment-dollar"></i><strong>{{ __('Package Price') }}:</strong>
                                    {{ __('NEGOTIABLE') }}</span></li>
                              @endif
                              <li>
                                <i class="fas fa-calendar-alt"></i><span>{{ __('Number of Days') }}:</span>
                                {{ $item->number_of_days }}
                              </li>
                              <li>
                                <i class="fas fa-user"></i><span>{{ __('Maximum Persons') }}:</span>
                                {{ $item->max_persons != null ? $item->max_persons : '-' }}
                              </li>
                            </ul>
                          </div>
                          <div class="col-md-4 col-xs-12 text-center">
                            <h6 class="price">
                              @if ($item->pricing_type != 'negotiable')
                                {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                {{ $item->package_price }}
                                {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                {{ '(' . __("$item->pricing_type") . ')' }}
                              @else
                                {{ __('NEGOTIABLE') }}
                              @endif
                            </h6>
                            <div class="ratings justify-content-center mb-1">

                              @if ($packageRating->package_rating_status == 1)
                                @php
                                  $avgRating = \App\Models\PackageManagement\PackageReview::where('package_id', $item->id)->avg('rating');
                                @endphp
                                <div class="rate">
                                  <div class="rating" style="width:{{ $avgRating * 20 }}%"></div>
                                </div>
                                <span class="ratings-total">({{ $avgRating == null ? '0.00' : $avgRating }})</span>
                              @endif
                            </div>
                            <a href="{{ route('package_details', [$item->id, $item->package_content->slug]) }}"
                              class="btn-text">{{ __('View More') }}</a>
                          </div>
                        </div><!-- package-default -->
                      @endforeach
                    @else
                      <h4 class="text-center">{{ __('No Package Found') }} </h4>
                    @endif
                  </div>

                  {{-- package loop start --}}
                  @foreach ($package_categories as $package_category)
                    @php
                      $pcategory_id = $package_category->id;
                      $c_packages = DB::table('packages')
                          ->join('package_contents', 'packages.id', 'package_contents.package_id')
                          ->where('packages.vendor_id', $vendor_id)
                          ->where('package_contents.package_category_id', $pcategory_id)
                          ->where('package_contents.language_id', $language_id)
                          ->select('packages.*', 'package_contents.package_category_id', 'package_contents.title', 'package_contents.slug', 'package_contents.description')
                          ->get();
                    @endphp
                    <div class="tab-pane fade" id="package{{ $pcategory_id }}">
                      @if (count($c_packages) > 0)
                        @foreach ($c_packages as $c_package)
                          <div class="row package-item mb-30 align-items-center">
                            <figure class="package-img col-md-2 col-xs-12">
                              <a href="{{ route('package_details', [$c_package->id, $c_package->slug]) }}">
                                <img src="{{ asset('assets/img/package/' . $c_package->featured_img) }}"
                                  alt="package">
                              </a>
                            </figure>
                            <div class="package-details col-md-6 col-xs-12 border-right">
                              <h4 class="package-title mb-2">
                                <a href="{{ route('package_details', [$c_package->id, $c_package->slug]) }}">
                                  {{ Str::limit($c_package->title, 70, '...') }}
                                </a>
                              </h4>
                              <ul class="package-list-group list-unstyled">
                                @if ($c_package->pricing_type != 'negotiable')
                                  <li><span><i
                                        class="fas fa-comment-dollar"></i><strong>{{ __('Package Price') . ' : ' }}</strong>
                                      {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                      {{ $c_package->package_price }}
                                      {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                      {{ '(' . __(strtoupper("$c_package->pricing_type")) . ')' }}</span></li>
                                @else
                                  <li><span><i
                                        class="fas fa-comment-dollar"></i><strong>{{ __('Package Price') . ' : ' }}</strong>
                                      {{ __('NEGOTIABLE') }}</span></li>
                                @endif
                                <li>
                                  <i class="fas fa-calendar-alt"></i><span>{{ __('Number of Days') . ' : ' }}</span>
                                  {{ $c_package->number_of_days }}
                                </li>
                                <li>
                                  <i class="fas fa-user"></i><span>{{ __('Maximum Persons') . ' : ' }}</span>
                                  {{ $c_package->max_persons != null ? $c_package->max_persons : '-' }}
                                </li>
                              </ul>
                            </div>
                            <div class="col-md-4 col-xs-12 text-center">
                              <h6 class="price">
                                @if ($c_package->pricing_type != 'negotiable')
                                  {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}
                                  {{ $c_package->package_price }}
                                  {{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                                  {{ '(' . __(strtoupper("$c_package->pricing_type")) . ')' }}
                                @else
                                  {{ __('NEGOTIABLE') }}
                                @endif
                              </h6>
                              <div class="ratings justify-content-center mb-1">
                                @if ($packageRating->package_rating_status == 1)
                                  @php
                                    $avgRating = \App\Models\PackageManagement\PackageReview::where('package_id', $c_package->id)->avg('rating');
                                  @endphp
                                  <div class="rate">
                                    <div class="rating" style="width:{{ $avgRating * 20 }}%"></div>
                                  </div>
                                  <span class="ratings-total">({{ $avgRating == null ? '0.00' : $avgRating }})</span>
                                @endif
                              </div>
                              <a href="{{ route('package_details', [$c_package->id, $c_package->slug]) }}"
                                class="btn-text">{{ __('View More') }}</a>
                            </div>
                          </div><!-- package-default -->
                        @endforeach
                      @else
                        <h4 class="text-center">{{ __('No Package Found') }} </h4>
                      @endif
                    </div>
                  @endforeach
                  {{-- package loop end --}}

                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <aside class="sidebar-widget-area">
              <div class="widget widget-author-details mb-30">
                <div class="author mb-20 text-center">
                  <figure class="author-img mb-3">
                    @if (request()->input('admin') == 'true')
                      @if ($vendor->image != null)
                        <img class="rounded-lg" src="{{ asset('assets/img/admins/' . $vendor->image) }}"
                          alt="Author">
                      @else
                        <img class="rounded-lg" src="{{ asset('assets/img/blank_user.jpg') }}" alt="Author">
                      @endif
                    @else
                      @if ($vendor->photo != null)
                        <img class="rounded-lg" src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}"
                          alt="Author">
                      @else
                        <img class="rounded-lg" src="{{ asset('assets/img/blank_user.jpg') }}" alt="Author">
                      @endif
                    @endif

                  </figure>
                  <div class="author-info">
                    <h4 class="pb-0">{{ $vendor->organization_name }}</h4>
                    <h5>{{ $vendor->username }}</h5>
                    <div class="rate mb-2 mx-auto">
                      <div class="rating" style="width:{{ $vendor_avg_rating * 20 }}%"></div>
                    </div>
                  </div>

                </div>
                <div>
                  @if ($vendor->details != null)
                    <div class="click-show">
                      <p class="text">
                        <b>{{ __('About') }}:</b> {{ $vendor->details }}
                      </p>
                    </div>
                    <div class="read-more-btn"><span>{{ __('Read more') }}</span></div>
                  @endif

                </div>
                <ul class="toggle-list list-unstyled mt-3">
                  <li>
                    <span class="first">

                      @if ($all_rooms->count() <= 1)
                        {{ __('Room') . ':' }}
                      @else
                        {{ __('Rooms') . ':' }}
                      @endif
                    </span>
                    <span class="last">{{ $all_rooms->count() }}

                    </span>
                  </li>

                  <li>
                    <span class="first">
                      @if ($all_packages->count() <= 1)
                        {{ __('Package') . ':' }}
                      @else
                        {{ __('Packages') . ':' }}
                      @endif
                    </span>
                    <span class="last">{{ $all_packages->count() }}

                    </span>
                  </li>
                  @if (request()->input('admin') != 'true')
                    @if ($vendor->show_email_addresss == 1)
                      <li>
                        <span class="first">{{ __('Email') . ' : ' }} </span>
                        <span class="last font-sm"><a href="mailto:{{ $vendor->email }}"
                            title="{{ $vendor->email }}">{{ $vendor->email }}</a></span>
                      </li>
                    @endif
                    @if ($vendor->show_phone_number == 1)
                      <li>
                        <span class="first">{{ __('Phone') . ' : ' }} </span>
                        <span class="last font-sm"><a href="tel:{{ $vendor->phone }}"
                            title="{{ $vendor->phone }}">{{ $vendor->phone }}</a></span>
                      </li>
                    @endif
                    @if (!empty($vendor->city) || !empty($vendor->country))
                      <li>
                        <span class="first">{{ __('Location') . ' : ' }} </span>
                        <span class="last">
                          @if (!empty($vendor->city))
                            {{ $vendor->city }}
                          @endif
                          @if (!empty($vendor->city) && !empty($vendor->country))
                            {{ ',' }}
                          @endif
                          @if (!empty($vendor->country))
                            {{ $vendor->country }}
                          @endif
                        </span>
                      </li>
                    @endif
                    <li>
                      <span class="first">{{ __('Member since') . ':' }} </span>
                      <span class="last">{{ date('dS F Y', strtotime($vendor->created_at)) }}</span>
                    </li>
                  @else
                    <li>
                      <span class="first">{{ __('Email') . ' : ' }} </span>
                      <span class="last font-sm"><a href="mailto:{{ $vendor->email }}"
                          title="{{ $vendor->email }}">{{ $vendor->email }}</a></span>
                    </li>
                    <li>
                      <span class="first">{{ __('Location') . ' : ' }} </span>
                      <span class="last">
                        {{ $vendor->address }}
                      </span>
                    </li>
                  @endif
                </ul>

                @if ($vendor->show_contact_form == 1 || request()->input('admin') == 'true')
                  <div class="btn-groups text-center mt-3">
                    <button type="button" class="btn filled-btn w-100 mb-2" title="Title" data-toggle="modal"
                      data-target="#contactModal">{{ __('Contact Now') }}</button>
                  </div>
                @endif
              </div>

            </aside>
          </div>
        </div>
      </div>
    </div>
    <!-- Author-single-area start -->

  </main>
  <!-- Main Wrap end -->

  {{-- modal --}}
  <!-- Contact Modal -->
  <div class="contact-modal modal fade" id="contactModal" tabindex="-1" role="dialog"
    aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="contactModalLabel">{{ __('Contact Now') . '!' }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="contact-wrapper">
            <div class="">
              <form action="{{ route('vendor.contact.message') }}" method="POST">
                @csrf
                <input type="hidden" name="vendor_email" value="{{ $vendor->email }}">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <input type="text" class="form-control" placeholder="{{ __('Name') }}" name="name"
                        required>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <input type="email" class="form-control" placeholder="{{ __('Email') }}" name="email"
                        required>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <input type="text" class="form-control" placeholder="{{ __('Subject') }}" name="subject"
                        required>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <textarea name="message" class="form-control" rows="3" placeholder="{{ __('Comment') }}"></textarea>
                    </div>
                  </div>
                  <div class="col-lg-12 text-center">
                    <button class="btn filled-btn w-100" type="submit" title="Submit">{{ __('Submit') }}</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- modal end --}}
@endsection
