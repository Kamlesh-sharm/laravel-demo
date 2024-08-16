@extends('frontend.layout')

@section('pageHeading')
  {{ __('Support Ticket') }}
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
            <li>{{ __('Support Tickets') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!--====== Start Dashboard Section ======-->

    <section class="user-dashboard">
      <div class="container">
        <div class="row">
          @include('frontend.user.side_navbar')

          <div class="col-lg-9">
            <div class="row">
              <div class="col-lg-12">
                <div class="user-profile-details">
                  <div class="account-info">
                    <div class="title support-header align-items-center">
                      <h4>{{ __('Recent Package Bookings') }}</h4>
                      <p><a href="{{ route('user.support_tickert.create') }}" class="btn btn-success p-2"><i
                            class="fas fa-plus"></i> {{ __('Submit Ticket') }}</a></p>
                    </div>

                    <div class="main-info">
                      <div class="main-table">
                        <div class="table-responsive">
                          <table id="dashboard-datatable"
                            class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                            <thead>
                              <tr>
                                <th>{{ __('Ticket ID') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Message') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($collection as $item)
                                <tr>
                                  <td>{{ $item->id }}</td>
                                  <td>{{ $item->subject }}</td>
                                  @if ($item->status == 1)
                                    <td><span class="badge badge-warning">{{ __('Pending') }}</span></td>
                                  @elseif($item->status == 2)
                                    <td><span class="badge badge-success">{{ __('Open') }}</span></td>
                                  @else
                                    <td><span class="badge badge-danger">{{ __('Closed') }}</span></td>
                                  @endif
                                  <td>
                                    @php
                                      $status = App\Models\SupportTicketStatus::where('id', 1)->first();
                                    @endphp

                                    <a href="{{ $status->support_ticket_status == 'active' ? route('user.support_ticket.message', $item->id) : '' }}"
                                      class="btn btn-primary mb-1"><i class="fas fa-envelope"></i></a>
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--====== End Dashboard Section ======-->
  </main>
@endsection
