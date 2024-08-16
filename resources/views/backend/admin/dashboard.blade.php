@extends('backend.layout')

@section('content')
  <div class="mt-2 mb-4">
    <h2 class="text-white pb-2">{{ __('Welcome back,') }}
      {{ Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name . '!' }}</h2>
  </div>

  {{-- dashboard information start --}}
  <div class="row">

    @if (empty($admin->role) || (!empty($permissions) && in_array('Lifetime Earnings', $permissions)))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-primary card-round" href="{{ route('admin.monthly_earning') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Lifetime Earnings') }}</p>
                  <h4 class="card-title">
                    {{ $websiteInfo->base_currency_symbol_position == 'left' ? $websiteInfo->base_currency_symbol : '' }}
                    {{ $earning->total_revenue }}
                    {{ $websiteInfo->base_currency_symbol_position == 'right' ? $websiteInfo->base_currency_symbol : '' }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif
    @if (empty($admin->role) || (!empty($permissions) && in_array('Total Profit', $permissions)))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-success card-round" href="{{ route('admin.monthly_profit') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-dollar-sign"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Profit') }}</p>
                  <h4 class="card-title">
                    {{ $websiteInfo->base_currency_symbol_position == 'left' ? $websiteInfo->base_currency_symbol : '' }}
                    {{ $earning->total_earning }}
                    {{ $websiteInfo->base_currency_symbol_position == 'right' ? $websiteInfo->base_currency_symbol : '' }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (empty($admin->role) || (!empty($permissions) && in_array('Transaction', $permissions)))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-primary card-round" href="{{ route('admin.transcation') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fal fa-exchange-alt"></i>
                </div>
              </div>

              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Total Transaction') }}</p>
                  <h4 class="card-title">{{ $transcation_count }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (empty($admin->role) || (!empty($permissions) && in_array('Rooms Management', $permissions)))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-danger card-round" href="{{ route('admin.rooms_management.rooms') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-hotel"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Rooms') }}</p>
                  <h4 class="card-title">{{ $roomsCount }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (empty($admin->role) || (!empty($permissions) && in_array('Room Bookings', $permissions)))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-info card-round" href="{{ route('admin.room_bookings.all_bookings') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="far fa-calendar-alt"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('All Room Bookings') }}</p>
                  <h4 class="card-title">{{ $allRbCount }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-secondary card-round" href="{{ route('admin.room_bookings.paid_bookings') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="far fa-calendar-check"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Paid Room Bookings') }}</p>
                  <h4 class="card-title">{{ $allPbCount }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (empty($admin->role) || (!empty($permissions) && in_array('Packages Management', $permissions)))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-success card-round" href="{{ route('admin.packages_management.packages') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="fas fa-plane-departure"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Packages') }}</p>
                  <h4 class="card-title">{{ $packagesCount }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif

    @if (empty($admin->role) || (!empty($permissions) && in_array('Package Bookings', $permissions)))
      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-warning card-round" href="{{ route('admin.package_bookings.all_bookings') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="far fa-calendar-alt"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('All Package Bookings') }}</p>
                  <h4 class="card-title">{{ $allPbCount }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-sm-6 col-md-4">
        <a class="card card-stats card-danger card-round" href="{{ route('admin.package_bookings.paid_bookings') }}">
          <div class="card-body">
            <div class="row">
              <div class="col-5">
                <div class="icon-big text-center">
                  <i class="far fa-calendar-check"></i>
                </div>
              </div>
              <div class="col-7 col-stats">
                <div class="numbers">
                  <p class="card-category">{{ __('Paid Package Bookings') }}</p>
                  <h4 class="card-title">{{ $paidPbCount }}</h4>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endif
  </div>

  <div class="row">
    @if (empty($admin->role) || (!empty($permissions) && in_array('Room Bookings', $permissions)))
      <div class="col-lg-6">
        <div class="row row-card-no-pd">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="card-head-row">
                  <h4 class="card-title">{{ __('Recent Room Bookings') }}</h4>
                </div>
                <p class="card-category">
                  {{ __('10 latest room bookings') }}
                </p>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    @if (count($rbookings) == 0)
                      <h3 class="text-center">{{ __('NO ROOM BOOKING FOUND!') }}</h3>
                    @else
                      <div class="table-responsive">
                        <table class="table table-striped mt-3">
                          <thead>
                            <tr>
                              <th scope="col">{{ __('Room') }}</th>
                              <th scope="col">{{ __('Rent') }}</th>
                              <th scope="col">{{ __('Payment Status') }}</th>
                              <th scope="col">{{ __('Actions') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($rbookings as $booking)
                              <tr>
                                <td>
                                  @php
                                    $roomInfo = $booking->hotelRoom->roomContent->where('language_id', $defaultLang->id)->first();
                                  @endphp
                                  @if ($roomInfo)
                                    <a href="{{ route('room_details', ['id' => $roomInfo->room_id, 'slug' => $roomInfo->slug]) }}"
                                      target="_blank">{{ strlen($roomInfo->title) > 25 ? mb_substr($roomInfo->title, 0, 25, 'utf-8') . '...' : $roomInfo->title }}</a>
                                  @endif
                                </td>
                                <td>{{ $booking->currency_text_position == 'left' ? $booking->currency_text : '' }}
                                  {{ $booking->grand_total }}
                                  {{ $booking->currency_text_position == 'right' ? $booking->currency_text : '' }}</td>
                                <td>
                                  @if ($booking->gateway_type == 'online')
                                    @if ($booking->payment_status == 1)
                                      <h2 class="d-inline-block"><span
                                          class="badge badge-success">{{ __('Paid') }}</span></h2>
                                    @else
                                      <h2 class="d-inline-block"><span
                                          class="badge badge-danger">{{ __('Unpaid') }}</span></h2>
                                    @endif
                                  @else
                                    @if ($booking->payment_status != 1)
                                      <form class="d-inline-block paymentStatusForm"
                                        action="{{ route('admin.room_bookings.update_payment_status') }}"
                                        method="post">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                        <select
                                          class="form-control form-control-sm {{ $booking->payment_status == 1 ? 'bg-success' : 'bg-danger' }} paymentStatusBtn"
                                          name="payment_status">
                                          <option value="1" {{ $booking->payment_status == 1 ? 'selected' : '' }}>
                                            {{ __('Paid') }}
                                          </option>
                                          <option value="0" {{ $booking->payment_status == 0 ? 'selected' : '' }}>
                                            {{ __('Unpaid') }}
                                          </option>
                                        </select>
                                      </form>
                                    @else
                                      @if ($booking->payment_status == 1)
                                        <h2 class="d-inline-block"><span
                                            class="badge badge-success">{{ __('Paid') }}</span>
                                        </h2>
                                      @else
                                        <h2 class="d-inline-block"><span
                                            class="badge badge-danger">{{ __('Unpaid') }}</span>
                                        </h2>
                                      @endif
                                    @endif
                                  @endif
                                </td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                      id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                      aria-expanded="false">
                                      {{ __('Select') }}
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                      <a href="{{ route('admin.room_bookings.booking_details_and_edit', ['id' => $booking->id]) }}"
                                        class="dropdown-item">{{ __('Details') }}</a>

                                      <a href="{{ asset('assets/invoices/rooms/' . $booking->invoice) }}"
                                        class="dropdown-item" target="_blank">{{ __('Invoice') }}</a>

                                      <a href="#" class="dropdown-item mailBtn" data-target="#mailModal"
                                        data-toggle="modal"
                                        data-customer_email="{{ $booking->customer_email }}">{{ __('Send Mail') }}</a>

                                      <form class="deleteForm d-block"
                                        action="{{ route('admin.room_bookings.delete_booking', ['id' => $booking->id]) }}"
                                        method="post">
                                        @csrf
                                        <button type="submit" class="deleteBtn">
                                          {{ __('Delete') }}
                                        </button>
                                      </form>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    @if (empty($admin->role) || (!empty($permissions) && in_array('Package Bookings', $permissions)))
      <div class="col-lg-6">
        <div class="row row-card-no-pd">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <div class="card-head-row">
                  <h4 class="card-title">{{ __('Recent Package Bookings') }}</h4>
                </div>
                <p class="card-category">
                  {{ __('10 latest package bookings') }}
                </p>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    @if (count($pbookings) == 0)
                      <h3 class="text-center">{{ __('NO PACKAGE BOOKING FOUND!') }}</h3>
                    @else
                      <div class="table-responsive">
                        <table class="table table-striped mt-3">
                          <thead>
                            <tr>
                              <th scope="col">{{ __('Package') }}</th>
                              <th scope="col">{{ __('Cost') }}</th>
                              <th scope="col">{{ __('Payment Status') }}</th>
                              <th scope="col">{{ __('Actions') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($pbookings as $booking)
                              <tr>
                                <td>
                                  @php
                                    $packageInfo = $booking->tourPackage->packageContent->where('language_id', $defaultLang->id)->first();
                                  @endphp
                                  @if ($packageInfo)
                                    <a href="{{ route('package_details', ['id' => $packageInfo->package_id, 'slug' => $packageInfo->slug]) }}"
                                      target="_blank">{{ strlen($packageInfo->title) > 25 ? mb_substr($packageInfo->title, 0, 25, 'utf-8') . '...' : $packageInfo->title }}</a>
                                  @endif
                                </td>
                                <td>{{ $booking->currency_text_position == 'left' ? $booking->currency_text : '' }}
                                  {{ $booking->grand_total }}
                                  {{ $booking->currency_text_position == 'right' ? $booking->currency_text : '' }}</td>
                                <td>
                                  @if ($booking->gateway_type == 'online')
                                    @if ($booking->payment_status == 1)
                                      <h2 class="d-inline-block"><span
                                          class="badge badge-success">{{ __('Paid') }}</span></h2>
                                    @else
                                      <h2 class="d-inline-block"><span
                                          class="badge badge-danger">{{ __('Unpaid') }}</span></h2>
                                    @endif
                                  @else
                                    @if ($booking->payment_status == 0)
                                      <form class="d-inline-block paymentStatusForm"
                                        action="{{ route('admin.package_bookings.update_payment_status') }}"
                                        method="post">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                        <select
                                          class="form-control form-control-sm {{ $booking->payment_status == 1 ? 'bg-success' : 'bg-danger' }} paymentStatusBtn"
                                          name="payment_status">
                                          <option value="1" {{ $booking->payment_status == 1 ? 'selected' : '' }}>
                                            {{ __('Paid') }}
                                          </option>
                                          <option value="0" {{ $booking->payment_status == 0 ? 'selected' : '' }}>
                                            {{ __('Unpaid') }}
                                          </option>
                                        </select>
                                      </form>
                                    @else
                                      @if ($booking->payment_status == 1)
                                        <h2 class="d-inline-block"><span
                                            class="badge badge-success">{{ __('Paid') }}</span>
                                        </h2>
                                      @else
                                        <h2 class="d-inline-block"><span
                                            class="badge badge-danger">{{ __('Unpaid') }}</span>
                                        </h2>
                                      @endif
                                    @endif
                                  @endif
                                </td>
                                <td>
                                  <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                      id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                      aria-expanded="false">
                                      {{ __('Select') }}
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                      <a href="{{ route('admin.package_bookings.booking_details', ['id' => $booking->id]) }}"
                                        class="dropdown-item">{{ __('Details') }}</a>

                                      <a href="{{ asset('assets/invoices/packages/' . $booking->invoice) }}"
                                        class="dropdown-item" target="_blank">{{ __('Invoice') }}</a>

                                      <a href="#" class="dropdown-item mailBtn" data-target="#mailModal"
                                        data-toggle="modal"
                                        data-customer_email="{{ $booking->customer_email }}">{{ __('Send Mail') }}</a>

                                      <form class="deleteForm d-block"
                                        action="{{ route('admin.package_bookings.delete_booking', ['id' => $booking->id]) }}"
                                        method="post">
                                        @csrf
                                        <button type="submit" class="deleteBtn">
                                          {{ __('Delete') }}
                                        </button>
                                      </form>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
  {{-- dashboard information end --}}

  @includeIf('backend.rooms.send_mail')
@endsection
