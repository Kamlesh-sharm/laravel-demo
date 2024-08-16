@extends('frontend.layout')

@section('pageHeading')
  {{ __('Payment Success') }}
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
          <h1>{{ __('Success') }}</h1>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    {{-- purchase success message start --}}
    <div class="purchase-message">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="purchase-success">
              <div class="icon text-success"><i class="far fa-check-circle"></i></div>
              <h2>{{ __('Success') . '!' }}</h2>
              @if (request()->input('type') == 'offline')
                <p>{{ __('We have received your booking request.') }}</p>
              @else
                <p>{{ __('Your transaction was successful.') }}</p>
              @endif

              <p>{{ __('We have sent you a mail with an invoice.') }}</p>

              @if (request()->input('type') == 'offline')
                <p>{{ __('You will be notified via mail once it is approved.') }}</p>
              @endif
              <p class="mt-4">{{ __('Thank You.') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- purchase success message end --}}
  </main>
@endsection
