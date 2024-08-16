@extends('frontend.layout')

@section('pageHeading')
  {{ $details->name }}
@endsection

@section('meta-keywords', "$details->meta_keywords")
@section('meta-description', "$details->meta_description")

@section('content')
  <main>
    <!-- Breadcrumb Section Start -->
    <section class="breadcrumb-area d-flex align-items-center position-relative bg-img-center"
      style="background-image: url({{ asset('assets/img/' . $breadcrumbInfo->breadcrumb) }});">
      <div class="container">
        <div class="breadcrumb-content text-center">
          <h1>{{ strlen($details->name) > 30 ? mb_substr($details->name, 0, 30) . '...' : $details->name }}</h1>

          <ul class="list-inline">
            <li><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li><i class="far fa-angle-double-right"></i></li>
            <li>{{ strlen($details->name) > 30 ? mb_substr($details->name, 0, 30) . '...' : $details->name }}</li>
          </ul>
        </div>
      </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="pt-100 pb-100">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="custom-page-content">
              <div class="summernote-content">
                {!! $details->body !!}
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </main>
@endsection
