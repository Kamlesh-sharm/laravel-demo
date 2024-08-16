@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Page Headings') }}</h4>
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
        <a href="#">{{ __('Basic Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Page Headings') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.basic_settings.update_page_headings') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update Page Headings') }}</div>
              </div>

              <div class="col-lg-2">
                @includeIf('backend.partials.languages')
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="form-group">
                  <label>{{ __('Blogs Title*') }}</label>
                  <input class="form-control" name="blogs_title" value="{{ $data != null ? $data->blogs_title : '' }}">
                  @if ($errors->has('blogs_title'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('blogs_title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('About Us Title*') }}</label>
                  <input class="form-control" name="about_us_title"
                    value="{{ $data != null ? $data->about_us_title : '' }}">
                  @if ($errors->has('about_us_title'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('about_us_title') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label>{{ __('Contact Us Title*') }}</label>
                  <input class="form-control" name="contact_us_title"
                    value="{{ $data != null ? $data->contact_us_title : '' }}">
                  @if ($errors->has('contact_us_title'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('contact_us_title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('FAQs Title*') }}</label>
                  <input class="form-control" name="faqs_title" value="{{ $data != null ? $data->faqs_title : '' }}">
                  @if ($errors->has('faqs_title'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('faqs_title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Gallery Title*') }}</label>
                  <input class="form-control" name="gallery_title"
                    value="{{ $data != null ? $data->gallery_title : '' }}">
                  @if ($errors->has('gallery_title'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('gallery_title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Rooms Title*') }}</label>
                  <input class="form-control" name="rooms_title" value="{{ $data != null ? $data->rooms_title : '' }}">
                  @if ($errors->has('rooms_title'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('rooms_title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Services Title*') }}</label>
                  <input class="form-control" name="services_title"
                    value="{{ $data != null ? $data->services_title : '' }}">
                  @if ($errors->has('services_title'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('services_title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Packages Title*') }}</label>
                  <input class="form-control" name="packages_title"
                    value="{{ $data != null ? $data->packages_title : '' }}">
                  @if ($errors->has('packages_title'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('packages_title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Error Page Title*') }}</label>
                  <input class="form-control" name="error_page_title"
                    value="{{ $data != null ? $data->error_page_title : '' }}">
                  @if ($errors->has('error_page_title'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('error_page_title') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
