@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Video Section') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Video Section') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Video Section') }}</div>
            </div>

            <div class="col-lg-2">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form
                id="ajaxForm"
                action="{{ route('admin.home_page.update_booking_section', ['language' => request()->input('language')]) }}"
                method="post"
              >
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Title*') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="booking_section_title"
                    value="{{ $data != null ? $data->booking_section_title : '' }}"
                  >
                  <p id="err_booking_section_title" class="em text-danger mt-1 mb-0"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Subtitle*') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="booking_section_subtitle"
                    value="{{ $data != null ? $data->booking_section_subtitle : '' }}"
                  >
                  <p id="err_booking_section_subtitle" class="em text-danger mt-1 mb-0"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Button*') }}</label>
                  <input
                    type="text"
                    class="form-control"
                    name="booking_section_button"
                    value="{{ $data != null ? $data->booking_section_button : '' }}"
                  >
                  <p id="err_booking_section_button" class="em text-danger mt-1 mb-0"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Button URL*') }}</label>
                  <input
                    type="url"
                    class="form-control ltr"
                    name="booking_section_button_url"
                    value="{{ $data != null ? $data->booking_section_button_url : '' }}"
                  >
                  <p id="err_booking_section_button_url" class="em text-danger mt-1 mb-0"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Video URL*') }}</label>
                  <input
                    type="url"
                    class="form-control ltr"
                    name="booking_section_video_url"
                    value="{{ $data != null ? $data->booking_section_video_url : '' }}"
                  >
                  <p id="err_booking_section_video_url" class="em text-danger mt-1 mb-0"></p>
                  <p class="text-warning mt-2 mb-0">{{ __('Link will be formatted automatically after submitting the form.') }}</p>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="submitBtn" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
