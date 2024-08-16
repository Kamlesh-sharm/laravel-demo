@extends('backend.layout')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('User Details') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.register.user') }}">{{ __('Registered Users') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('User Details') }}</a>
      </li>
    </ul>

    <a href="{{ route('admin.register.user') }}" class="btn-md btn btn-primary"
      style="margin-left: auto;">{{ __('Back') }}</a>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-body text-center p-4">
              <img
                src="{{ !empty($user->image) ? asset('assets/img/users/' . $user->image) : asset('assets/img/users/profile.jpg') }}"
                alt="" width="100%">
            </div>
          </div>
        </div>


        <div class="col-md-9">
          <div class="row">

            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">{{ __('Information') }}</h4>
                </div>
                <div class="card-body">
                  <div class="row mb-2">
                    <div class="col-lg-2">
                      <strong>{{ __('Username:') }}</strong>
                    </div>
                    <div class="col-lg-10">
                      {{ $user->username ? $user->username : '-' }}
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-2">
                      <strong>{{ __('First Name:') }}</strong>
                    </div>
                    <div class="col-lg-10">
                      {{ $user->first_name ? $user->first_name : '-' }}
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-2">
                      <strong>{{ __('Last Name:') }}</strong>
                    </div>
                    <div class="col-lg-10">
                      {{ $user->last_name ? $user->last_name : '-' }}
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-2">
                      <strong>{{ __('Email:') }}</strong>
                    </div>
                    <div class="col-lg-10">
                      {{ $user->email ? $user->email : '-' }}
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-2">
                      <strong>{{ __('Phone:') }}</strong>
                    </div>
                    <div class="col-lg-10">
                      {{ $user->contact_number ? $user->contact_number : '-' }}
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-2">
                      <strong>{{ __('Address:') }}</strong>
                    </div>
                    <div class="col-lg-10">
                      {{ $user->address ? $user->address : '-' }}
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-2">
                      <strong>{{ __('City:') }}</strong>
                    </div>
                    <div class="col-lg-10">
                      {{ $user->city ? $user->city : '-' }}
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-2">
                      <strong>{{ __('State:') }}</strong>
                    </div>
                    <div class="col-lg-10">
                      {{ $user->state ? $user->state : '-' }}
                    </div>
                  </div>
                  <div class="row mb-2">
                    <div class="col-lg-2">
                      <strong>{{ __('Country:') }}</strong>
                    </div>
                    <div class="col-lg-10">
                      {{ $user->country ? $user->country : '-' }}
                    </div>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6">
      <div class="row row-card-no-pd">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="card-head-row">
                <h4 class="card-title">{{ __('Recent Room Bookings') }}</h4>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  @php
                    $rbookings = $user
                        ->bookHotelRoom()
                        ->orderBy('id', 'DESC')
                        ->limit(10)
                        ->get();
                  @endphp
                  @if (count($rbookings) == 0)
                    <h3 class="text-center">{{ __('NO ROOM BOOKING FOUND!') }}</h3>
                  @else
                    <div class="table-responsive">
                      <table class="table table-striped mt-3">
                        <thead>
                          <tr>
                            <th scope="col">{{ __('Room') }}</th>
                            <th scope="col">{{ __('Rent / Night') }}</th>
                            <th scope="col">{{ __('Payment Status') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($rbookings as $booking)
                            <tr>
                              <td>
                                @php
                                  $title = $booking->hotelRoom->roomContent->where('language_id', $defaultLang->id)->first()->title;
                                @endphp
                                {{ strlen($title) > 20 ? mb_substr($title, 0, 20, 'utf-8') . '...' : $title }}
                              </td>
                              <td>{{ $booking->currency_text_position == 'left' ? $booking->currency_text : '' }}
                                {{ $booking->grand_total }}
                                {{ $booking->currency_text_position == 'right' ? $booking->currency_text : '' }}
                              </td>
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
                                  <form id="paymentStatusForm{{ $booking->id }}" class="d-inline-block"
                                    action="{{ route('admin.room_bookings.update_payment_status') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                    <select
                                      class="form-control form-control-sm {{ $booking->payment_status == 1 ? 'bg-success' : 'bg-danger' }}"
                                      name="payment_status"
                                      onchange="document.getElementById('paymentStatusForm{{ $booking->id }}').submit();">
                                      <option value="1" {{ $booking->payment_status == 1 ? 'selected' : '' }}>
                                        {{ __('Paid') }}
                                      </option>
                                      <option value="0" {{ $booking->payment_status == 0 ? 'selected' : '' }}>
                                        {{ __('Unpaid') }}
                                      </option>
                                    </select>
                                  </form>
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

    <div class="col-lg-6">
      <div class="row row-card-no-pd">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="card-head-row">
                <h4 class="card-title">{{ __('Recent Package Bookings') }}</h4>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  @php
                    $pbookings = $user
                        ->bookTourPackage()
                        ->orderBy('id', 'DESC')
                        ->limit(10)
                        ->get();
                  @endphp
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
                                  $title = $booking->tourPackage->packageContent->where('language_id', $defaultLang->id)->first()->title;
                                @endphp
                                {{ strlen($title) > 20 ? mb_substr($title, 0, 20, 'utf-8') . '...' : $title }}
                              </td>
                              <td>{{ $booking->currency_text_position == 'left' ? $booking->currency_text : '' }}
                                {{ $booking->grand_total }}
                                {{ $booking->currency_text_position == 'right' ? $booking->currency_text : '' }}
                              </td>
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
                                  <form id="paymentStatusForm{{ $booking->id }}" class="d-inline-block"
                                    action="{{ route('admin.package_bookings.update_payment_status') }}"
                                    method="post">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                                    <select
                                      class="form-control form-control-sm {{ $booking->payment_status == 1 ? 'bg-success' : 'bg-danger' }}"
                                      name="payment_status"
                                      onchange="document.getElementById('paymentStatusForm{{ $booking->id }}').submit();">
                                      <option value="1" {{ $booking->payment_status == 1 ? 'selected' : '' }}>
                                        {{ __('Paid') }}
                                      </option>
                                      <option value="0" {{ $booking->payment_status == 0 ? 'selected' : '' }}>
                                        {{ __('Unpaid') }}
                                      </option>
                                    </select>
                                  </form>
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
  </div>
  @includeIf('backend.rooms.send_mail')
@endsection
