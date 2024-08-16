@extends('frontend.layout')

@section('pageHeading')
  {{ __('Dashboard') }}
@endsection

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center"
      style="background-image: url({{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }});">
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ __('Dashboard') }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Dashboard') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Dashboard Area Start -->
    <section class="user-dashboard">
      <div class="container">
        <div class="row">
          @include('frontend.user.side_navbar')

          <div class="col-lg-9">
            <div class="row mb-5">
              <div class="col-lg-12">
                <div class="user-profile-details">
                  <div class="account-info">
                    <div class="title">
                      <h4>{{ __('User Information') }}</h4>
                    </div>

                    <div class="main-info">
                      <ul class="list info-list">
                        @if (Auth::guard('web')->user()->first_name != null || Auth::guard('web')->user()->last_name != null)
                          <li>
                            <span>
                              <strong>{{ __('Name') . ' : ' }}</strong>
                            </span>
                            <span>
                              {{ Auth::guard('web')->user()->first_name . ' ' . Auth::guard('web')->user()->last_name }}
                            </span>
                          </li>
                        @endif

                        <li>
                          <span>
                            <strong>{{ __('Username') . ' : ' }}</strong>
                          </span>
                          <span>
                            {{ Auth::guard('web')->user()->username }}
                          </span>
                        </li>

                        <li>
                          <span>
                            <strong>{{ __('Email') . ' : ' }}</strong>
                          </span>
                          <span>
                            {{ Auth::guard('web')->user()->email }}
                          </span>
                        </li>

                        @if (Auth::guard('web')->user()->contact_number != null)
                          <li>
                            <span>
                              <strong>{{ __('Phone') . ' :' }}</strong>
                            </span>
                            <span>
                              {{ Auth::guard('web')->user()->contact_number }}
                            </span>
                          </li>
                        @endif

                        @if (Auth::guard('web')->user()->address != null)
                          <li>
                            <span>
                              <strong>{{ __('Address') . ' :' }}</strong>
                            </span>
                            <span>
                              {{ Auth::guard('web')->user()->address }}
                            </span>
                          </li>
                        @endif

                        @if (Auth::guard('web')->user()->city != null)
                          <li>
                            <span>
                              <strong>{{ __('City') . ' :' }}</strong>
                            </span>
                            <span>{{ Auth::guard('web')->user()->city }}</span>
                          </li>
                        @endif

                        @if (Auth::guard('web')->user()->state != null)
                          <li>
                            <span>
                              <strong>{{ __('State') . ' :' }}</strong>
                            </span>
                            <span>
                              {{ Auth::guard('web')->user()->state }}
                            </span>
                          </li>
                        @endif

                        @if (Auth::guard('web')->user()->country != null)
                          <li>
                            <span>
                              <strong>{{ __('Country') . ' :' }}</strong>
                            </span>
                            <span>
                              {{ Auth::guard('web')->user()->country }}
                            </span>
                          </li>
                        @endif
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <a href="{{ route('user.room_bookings') }}">
                  <div class="card card-box box-1">
                    <div class="card-info">
                      <h5>{{ __('Room Bookings') }}</h5>
                      <p>{{ $totalRoomBooking }}</p>
                    </div>
                  </div>
                </a>
              </div>

              <div class="col-md-4">
                <a href="{{ route('user.package_bookings') }}">
                  <div class="card card-box box-2">
                    <div class="card-info">
                      <h5>{{ __('Package Bookings') }}</h5>
                      <p>{{ $totalPackageBooking }}</p>
                    </div>
                  </div>
                </a>
              </div>
              @if ($support_ticket_status->support_ticket_status == 'active')
                <div class="col-md-4">
                  <a href="{{ route('user.support_tickert') }}">
                    <div class="card card-box box-3">
                      <div class="card-info">
                        <h5>{{ __('Support Tickets') }}</h5>
                        <p>{{ $totalSupportTickets }}</p>
                      </div>
                    </div>
                  </a>
                </div>
              @endif

            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Dashboard Area End -->
  </main>
@endsection
