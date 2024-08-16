@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Footer Text') }}</h4>
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
        <a href="#">{{ __('Footer') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Footer Text') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Footer Text') }}</div>
            </div>

            <div class="col-lg-2">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="ajaxForm"
                action="{{ route('admin.footer.update_footer_info', ['language' => request()->input('language')]) }}"
                method="post">
                @csrf
                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                  <label for="">{{ __('About Company*') }}</label>
                  <textarea class="form-control" name="about_company" rows="3" cols="80">{{ $data != null ? $data->about_company : '' }}</textarea>
                  <p id="err_about_company" class="em text-danger mt-2 mb-0"></p>
                </div>

                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                  <label for="">{{ __('Copyright Text*') }}</label>
                  <textarea id="copyrightSummernote" class="form-control summernote" name="copyright_text" data-height="80">{!! $data != null ? $data->copyright_text : '' !!}</textarea>
                  <p id="err_copyright_text" class="em text-danger mb-0"></p>
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
