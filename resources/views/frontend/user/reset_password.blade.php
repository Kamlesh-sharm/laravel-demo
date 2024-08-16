@extends('frontend.layout')

@section('pageHeading')
  {{ __('Reset Password') }}
@endsection

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section
      class="breadcrumb-area d-flex align-items-center position-relative bg-img-center"
      style="background-image: url({{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }});"
    >
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ __('Reset Password') }}</h1>
          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ __('Reset Password') }}</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Reset Password Area Start -->
    <div class="user-area-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="user-content">
              <form action="{{ route('user.reset_password_submit') }}" method="POST">
                @csrf

                <input type="hidden" name="password_code" value="{{Request::route('code')}}">
                <div class="input-box">
                  <label>{{ __('New Password') . '*' }}</label>
                  <input type="password" name="new_password">
                  @error('new_password')
                    <p class="mt-3 ml-2 text-danger">{{ $message }}</p>
                  @enderror
                </div>

                <div class="input-box">
                  <label>{{ __('Confirm New Password') . '*' }}</label>
                  <input type="password" name="new_password_confirmation">
                  @error('new_password_confirmation')
                    <p class="mt-3 ml-2 text-danger">{{ $message }}</p>
                  @enderror
                </div>

                <div class="input-box">
                  <button type="submit" class="btn">{{ __('submit') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Reset Password Area End -->
  </main>
@endsection
