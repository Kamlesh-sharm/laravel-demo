@extends('vendors.layout')

@section('content')
  <div class="mt-2 mb-4">
    <h2 class="pb-2">{{ __('Welcome back,') }} {{ Auth::guard('vendor')->user()->username . '!' }}</h2>
  </div>
  @if (Session::get('secret_login') != 1)
    @if (Auth::guard('vendor')->user()->status == 0 && $admin_setting->vendor_admin_approval == 1)
      <div class="mt-2 mb-4">
        <div class="alert alert-danger text-dark">
          {{ $admin_setting->admin_approval_notice != null ? $admin_setting->admin_approval_notice : 'Your account is deactive!' }}
        </div>
      </div>
    @endif
  @endif

  {{-- dashboard information start --}}
  <div class="row dashboard-items">
    <div class="col-md-4">
      <a href="{{ route('vendor.monthly_income') }}" class="text-decoration-none">
        <div class="card card-stats card-primary card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('My Balance ') }}</p>
                  <h4 class="card-title">
                    {{ $settings->base_currency_symbol_position == 'left' ? $settings->base_currency_symbol : '' }}
                    {{ Auth::guard('vendor')->user()->amount }}
                    {{ $settings->base_currency_symbol_position == 'right' ? $settings->base_currency_symbol : '' }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="{{ route('vendor.transcation') }}" class="text-decoration-none">
        <div class="card card-stats card-warning card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-exchange"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Transaction') }}</p>
                  <h4 class="card-title">
                    {{ $transcations }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="{{ route('vendor.rooms_management.rooms') }}" class="text-decoration-none">
        <div class="card card-stats card-success card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-hotel"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Room') }}</p>
                  <h4 class="card-title">
                    {{ $totalRoom }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="{{ route('vendor.room_bookings.all_bookings') }}" class="text-decoration-none">
        <div class="card card-stats card-danger card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="far fa-calendar-alt"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Room Booking') }}</p>
                  <h4 class="card-title">
                    {{ $totalRoomBooking }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="{{ route('vendor.packages_management.packages') }}" class="text-decoration-none">
        <div class="card card-stats card-secondary card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-plane-departure"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Package') }}</p>
                  <h4 class="card-title">
                    {{ $totalPackage }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="{{ route('vendor.package_bookings.all_bookings') }}" class="text-decoration-none">
        <div class="card card-stats card-info card-round">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="far fa-calendar-check"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Pacakge Booking') }}</p>
                  <h4 class="card-title">
                    {{ $totalPackageBooking }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  {{-- chart --}}
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Number of Room Bookings') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="roomBookingChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Income from Room Bookings') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="roomIncomeChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- chart --}}
  <div class="row">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Number of Package Bookings') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="packageBookingChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">
          <div class="card-title">{{ __('Income from Package Bookings') }} ({{ date('Y') }})</div>
        </div>

        <div class="card-body">
          <div class="chart-container">
            <canvas id="packageIncomeChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- chart js ----->
  <script type="text/javascript" src="{{ asset('assets/js/chart.min.js') }}"></script>

  <script>
    'use strict';
    const monthArr = {!! json_encode($months) !!};
    const bookingArr = {!! json_encode($bookings) !!};
    const incomeArr = {!! json_encode($incomes) !!};

    const pBookingArr = {!! json_encode($p_bookings) !!};
    const pIncomeArr = {!! json_encode($p_incomes) !!};
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/chart-init.js') }}"></script>
@endsection
