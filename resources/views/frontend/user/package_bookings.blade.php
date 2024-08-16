@extends('frontend.layout')

@section('pageHeading')
  {{ __('Package Bookings') }}
@endsection

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center"
      style="background-image: url({{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }});">
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ __('Package Bookings') }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Package Bookings') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- All Package Bookings Area Start -->
    <section class="user-dashboard">
      <div class="container">
        <div class="row">
          @include('frontend.user.side_navbar')

          <div class="col-lg-9">
            <div class="row">
              <div class="col-lg-12">
                @if (count($packageBookingInfos) == 0)
                  <div class="py-5 bg-light">
                    <h3 class="text-center">{{ __('No Package Booking Found!') }}</h3>
                  </div>
                @else
                  <div class="user-profile-details">
                    <div class="account-info">
                      <div class="title">
                        <h4>{{ __('Recent Package Bookings') }}</h4>
                      </div>

                      <div class="main-info">
                        <div class="main-table">
                          <div class="table-responsive">
                            <table id="dashboard-datatable"
                              class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                              <thead>
                                <tr>
                                  <th>{{ __('Booking Number') }}</th>
                                  <th>{{ __('Title') }}</th>
                                  <th>{{ __('Booking Date') }}</th>
                                  <th>{{ __('Booking Status') }}</th>
                                  <th>{{ __('Action') }}</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($packageBookingInfos as $info)
                                  <tr>
                                    <td class="pl-3">{{ '#' . $info->booking_number }}</td>

                                    @php
                                      $package = $info->tourPackage()->first();
                                      
                                      $packageDetails = $package
                                          ->packageContent()
                                          ->where('language_id', $langInfo->id)
                                          ->first();
                                      
                                      $packageTitle = $packageDetails->title;
                                    @endphp

                                    <td class="pl-3">
                                      <a target="_blank"
                                        href="{{ route('package_details', [$packageDetails->package_id, $packageDetails->slug]) }}">
                                        {{ strlen($packageTitle) > 20 ? mb_substr($packageTitle, 0, 20) . '...' : $packageTitle }}
                                      </a>
                                    </td>

                                    <td class="pl-3">{{ date_format($info->created_at, 'M d, Y') }}</td>

                                    {{-- if payment_status == 1 then, booking is complete.
                                      otherwise booking is pending --}}
                                    @if ($info->payment_status == 1)
                                      <td class="pl-3"><span class="complete">{{ __('Complete') }}</span></td>
                                    @else
                                      <td class="pl-3"><span class="pending">{{ __('Pending') }}</span></td>
                                    @endif

                                    <td class="pl-3">
                                      <a href="{{ route('user.package_booking_details', ['id' => $info->id]) }}"
                                        class="btn">
                                        {{ __('Details') }}
                                      </a>
                                    </td>
                                  </tr>
                                @endforeach
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- All Package Bookings Area End -->
  </main>
@endsection
