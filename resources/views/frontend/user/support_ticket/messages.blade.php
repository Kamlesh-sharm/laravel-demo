@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->support_ticket_details_page_title ?? __('Ticket Details') }}
  @else
    {{ __('Ticket Details') }}
  @endif
@endsection
@section('custom-style')
  <link rel="stylesheet" href="{{ asset('assets/css/support-ticket.css') }}">
@endsection
@section('content')
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
                  <div class="title">
                    <h4>{{ __('Support Ticket Details') }} - #{{ $ticket->id }}</h4>
                  </div>
                  <div class="main-info">
                    <div class="subject mb-1">
                      <h5>{{ $ticket->subject }}</h5>
                      <div class=" d-flex align-items-center">
                        @if ($ticket->status == 1)
                          <h6 class="badge badge-warning">{{ __('Pending') }}</h6>
                        @elseif($ticket->status == 2)
                          <h6 class="badge badge-primary">{{ __('Open') }}</h6>
                        @else
                          <h6 class="badge badge-success">{{ __('Closed') }}</h6>
                        @endif
                        <h6><span
                            class="badge badge-light">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d-M-Y H:s a') }}</span>
                        </h6>
                      </div>
                    </div>
                    <div class="description">
                      <div class="summernote-content">
                        {!! $ticket->description !!}
                      </div>
                    </div>
                    @if ($ticket->attachment != null)
                      <a href="{{ asset('assets/img/support-ticket/' . $ticket->attachment) }}" download="support.zip"
                        class="btn btn-primary p-2 mb-1 mt-2"><i class="fas fa-download"></i>
                        {{ __('Download Attachment') }}</a>
                    @endif
                    <hr>
                    <div class="message-section">
                      <h5>{{ __('Replies') }}</h5>
                      <div class="message-lists">
                        <div class="messages">
                          @if (count($ticket->messages) > 0)
                            @foreach ($ticket->messages as $reply)
                              @if ($reply->type == 2)
                                @php
                                  $admin = App\Models\Admin::where('id', $reply->user_id)->first();
                                @endphp
                                <div class="single-message">
                                  <div class="user-details">
                                    <div class="user-img">
                                      <img class="support-user-img"
                                        src="{{ $admin->image ? asset('assets/img/admins/' . $admin->image) : asset('assets/admin/img/propics/blank_user.jpg') }}"
                                        alt="">
                                    </div>
                                    <div class="user-infos">
                                      <h6 class="name">{{ $admin->username }}</h6>
                                      <span class="type"><i class="fas fa-user"></i>
                                        {{ $admin->id == 1 ? 'Super Admin' : $admin->role->name }}</span>
                                      <span
                                        class="badge badge-secondary">{{ \Carbon\Carbon::parse($reply->created_at)->format('d-M-Y H:s a') }}</span>
                                      @if ($ticket->attachment != null)
                                        <a href="{{ asset('assets/img/support-ticket/' . $reply->file) }}"
                                          download="support.zip" class="reply-download-btn"><i
                                            class="fas fa-download"></i>
                                          {{ __('Download') }}</a>
                                      @endif
                                    </div>
                                  </div>
                                  <div class="message">
                                    <div class="summernote-content">
                                      {!! $reply->reply !!}
                                    </div>
                                  </div>
                                </div>
                              @else
                                @php
                                  $user = App\Models\User::where('id', $ticket->user_id)->first();
                                @endphp
                                <div class="single-message">
                                  <div class="user-details">
                                    <div class="user-img">
                                      @if ($user->image != null)
                                        <img class="support-user-img"
                                          src="{{ asset('assets/img/users/' . $user->image) }}" alt="">
                                      @else
                                        <img class="support-user-img" src="{{ asset('assets/img/blank_user.jpg') }}"
                                          alt="">
                                      @endif
                                    </div>
                                    <div class="user-infos">
                                      <h6 class="name">{{ $user->username }}</h6>
                                      <span
                                        class="badge badge-secondary">{{ \Carbon\Carbon::parse($reply->created_at)->format('d-M-Y H:s a') }}</span>
                                      @if ($reply->file != null)
                                        <a href="{{ asset('assets/img/support-ticket/' . $reply->file) }}"
                                          download="support.zip" class="reply-download-btn"><i
                                            class="fas fa-download"></i>
                                          {{ __('Download') }}</a>
                                      @endif

                                    </div>
                                  </div>
                                  <div class="message">
                                    <div class="summernote-content">
                                      {!! $reply->reply !!}
                                    </div>
                                  </div>
                                </div>
                              @endif
                            @endforeach
                          @else
                            <h4>{{ __('No Message Found') }}</h4>
                          @endif
                        </div>
                        @if ($ticket->status == 2)
                          <hr>
                          <div class="reply-section">
                            <h5>{{ __('Reply') }}</h5>
                            <form action="{{ route('user.support_ticket.reply', $ticket->id) }}" method="POST"
                              enctype="multipart/form-data">
                              @csrf
                              <div class="form-group">
                                <label for="">{{ __('Reply') }} *</label>
                                <textarea name="reply" class="form-control tinymceInit"></textarea>
                                @error('reply')
                                  <p class="text-danger">{{ $message }}</p>
                          @endif
                        </div>
                        <div class="form-group">
                          <input type="file" name="file" class="form-control" accept=".zip">
                          <p class="text-danger">{{ __('Max upload size 5 MB') }}</p>
                          @error('file')
                            <p class="text-danger">{{ $message }}</p>
                            @endif
                          </div>
                          <div class="form-group">
                            <button type="submit" class="btn btn-success p-2"><i class="fas fa-retweet"></i>
                              {{ __('Reply') }}</button>
                          </div>
                          </form>
                        </div>
                      </div>
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
      </section>
      <!--====== End Dashboard Section ======-->
    @endsection
