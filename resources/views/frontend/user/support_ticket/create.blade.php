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
            <li>{{ __('Create Support Ticket') }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->
    <!-- Edit Profile Area Start -->
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
                      <h4>{{ __('Create a Support Ticket') }}</h4>
                    </div>

                    <div class="edit-info-area mt-3">
                      <form action="{{ route('user.support_ticket.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                          <div class="col-lg-6">
                            <label for="">{{ __('Email') . ' *' }}</label>
                            <input type="text" class="form_control" placeholder="" name="email"
                              value="{{ Auth::guard('web')->user()->email }}" readonly>
                            @error('email')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-6">
                            <label for="">{{ __('Subject') . ' *' }}</label>
                            <input type="text" class="form_control" placeholder="" name="subject">
                            @error('subject')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>

                          <div class="col-lg-12">
                            <label for="">{{ __('Description') . ' *' }}</label>
                            <input type="text" class="form_control tinymceInit" placeholder="" name="description">
                            @error('description')
                              <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                            @enderror
                          </div>


                          <div class="col-lg-12">
                            <div class="form-group">
                              <label for="">{{ __('Attachment') }}</label>
                              <input type="file" class="form_control" accept=".zip" name="attachment">
                              @error('attachment')
                                <p class="mb-3 ml-2 text-danger">{{ $message }}</p>
                              @enderror
                            </div>
                          </div>

                          <div class="col-lg-12">
                            <div class="form-button">
                              <button class="btn form-btn">{{ __('Submit') }}</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Edit Profile Area End -->
  </main>
@endsection
