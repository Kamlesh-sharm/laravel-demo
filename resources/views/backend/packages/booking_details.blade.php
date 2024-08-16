@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Booking Details') }}</h4>
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
        <a href="#">{{ __('Packages Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Package Bookings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Booking Details') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-6">
              <div class="card-title d-inline-block">
                {{ __('Booking Id') . ' #' }} {{ $details->booking_number }}
              </div>
            </div>
            <div class="col-md-6">
              <a class="btn btn-info btn-sm float-lg-right float-md-right float-left d-inline-block"
                href="{{ route('admin.package_bookings.all_bookings') }}">
                <span class="btn-label">
                  <i class="fas fa-backward"></i>
                </span>
                {{ __('Back') }}
              </a>
            </div>
          </div>


        </div>

        @php
          $position = $details->currency_text_position;
          $currency = $details->currency_text;
        @endphp

        <div class="card-body">
          <div class="payment-information">


            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('booking date') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ date_format($details->created_at, 'F d, Y') }}
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('package name :') }}</strong>
              </div>
              @php
                $packageInfo = $details->tourPackage->packageContent->where('language_id', $defaultLang->id)->first();
              @endphp
              <div class="col-lg-8"><a
                  href="{{ route('package_details', ['id' => $packageInfo->package_id, 'slug' => $packageInfo->slug]) }}"
                  target="_blank">{{ strlen($packageInfo->title) > 25 ? mb_substr($packageInfo->title, 0, 25, 'utf-8') . '...' : $packageInfo->title }}</a>
              </div>
            </div>


            @if ($packageCategoryName != null)
              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('package type :') }}</strong>
                </div>
                <div class="col-lg-8">{{ $packageCategoryName }}</div>
              </div>
            @endif

            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('number of visitors :') }}</strong>
              </div>
              <div class="col-lg-8">{{ $details->visitors }}</div>
            </div>


            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('subtotal :') }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ $details->subtotal }}{{ $position == 'right' ? ' ' . $currency : '' }}
              </div>
            </div>


            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('discount :') }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ $details->discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
              </div>
            </div>


            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('Customer Paid') . ' : ' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ $details->grand_total }}{{ $position == 'right' ? ' ' . $currency : '' }}
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('Received by Vendor') . ' : ' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ $details->received_amount }}{{ $position == 'right' ? ' ' . $currency : '' }}
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('Commission') }}
                  ({{ $details->commission_percentage }}%) : </strong>
              </div>
              <div class="col-lg-8">
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ $details->comission }}{{ $position == 'right' ? ' ' . $currency : '' }}
                ({{ __('Received by Admin') }})
              </div>
            </div>


            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('payment method :') }}</strong>
              </div>
              <div class="col-lg-8">{{ $details->payment_method }}</div>
            </div>


            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('payment status :') }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $details->payment_status == 1 ? 'Paid' : 'Unpaid' }}
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Customer Information') }}</div>
        </div>
        <div class="card-body">
          <div class="payment-information">
            @if (!is_null($details->user_id))
              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('username') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"><a
                    href="{{ route('register.user.view', $details->user_id) }}">{{ @$details->user->username }}</a>
                </div>
              </div>
            @endif

            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('name :') }}</strong>
              </div>
              <div class="col-lg-8">{{ convertUtf8($details->customer_name) }}</div>
            </div>


            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('email :') }}</strong>
              </div>
              <div class="col-lg-8"> {{ $details->customer_email }}</div>
            </div>


            <div class="row">
              <div class="col-lg-4">
                <strong class="text-capitalize">{{ __('phone : ') }}</strong>
              </div>
              <div class="col-lg-8"> {{ $details->customer_phone }}</div>
            </div>

            @if (!is_null($details->user_id))
              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('Address') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->user->address }}</div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('City') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->user->city }}</div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('State') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->user->state }}</div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('Country') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->user->country }}</div>
              </div>
            @endif

          </div>
        </div>

      </div>
    </div>
    @if (!is_null($details->vendor_id))
      <div class="col-md-3">
        <div class="card">
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Vendor Information') }}</div>
          </div>
          <div class="card-body">
            <div class="payment-information">
              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('username') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> <a
                    href="{{ route('admin.vendor_management.vendor_details', ['id' => $details->vendor_id, 'language' => $defaultLang->code]) }}">{{ @$details->vendor->username }}</a>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('Name') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->vendor->name }}</div>
              </div>


              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('email :') }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->vendor->email }}</div>
              </div>


              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('Phone') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->vendor->phone }}</div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('Address') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->vendor->address }}</div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('City') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->vendor->city }}</div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('State') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->vendor->state }}</div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <strong class="text-capitalize">{{ __('Country') . ' : ' }}</strong>
                </div>
                <div class="col-lg-8"> {{ @$details->vendor->country }}</div>
              </div>

            </div>
          </div>

        </div>
      </div>
    @endif
  </div>
@endsection
