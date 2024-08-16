@extends('frontend.layout')

@section('pageHeading')
  {{__('404')}}
@endsection

@section('content')
@php
    $breadcrumbInfo = App\Traits\MiscellaneousTrait::getBreadcrumb();
@endphp
<main>
    <!-- Breadcrumb Section Start -->
    <section
      class="breadcrumb-area d-flex align-items-center position-relative bg-img-center"
      style="background-image: url({{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }});"
    >
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{__('Page Not Found')}}</h1>

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{__('404')}}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!--    Error section start   -->
    <div class="error-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="not-found">
                        <img src="{{asset('assets/img/404.png')}}" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="error-txt">
                        <div class="oops">
                        <img src="{{asset('assets/img/oops.png')}}" alt="">
                        </div>
                        <h2>{{__("You're lost")}}...</h2>
                        <p>{{__("The page you are looking for might have been moved, renamed, or might never existed.")}}</p>
                        <a href="{{route('index')}}" class="go-home-btn">{{__("Back Home")}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    Error section end   -->
</main>

@endsection
