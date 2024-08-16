@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('FAQ Section') }}</h4>
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
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('FAQ Section') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update FAQ Section') }}</div>
            </div>

            <div class="col-lg-2">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="faqSecForm"
                action="{{ route('admin.home_page.update_faq_section', ['language' => request()->input('language')]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <div class="thumb-preview">
                    @if (!empty($data->faq_section_image))
                      <img src="{{ asset('assets/img/faq_section/' . $data->faq_section_image) }}" alt="image"
                        class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @endif
                  </div>
                  <br><br>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="faq_section_image">
                    </div>
                  </div>
                  @if ($errors->has('faq_section_image'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('faq_section_image') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label for="">{{ __('FAQ Section Title*') }}</label>
                  <input type="text" class="form-control" name="faq_section_title"
                    value="{{ $data != null ? $data->faq_section_title : '' }}">
                  @if ($errors->has('faq_section_title'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('faq_section_title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label for="">{{ __('FAQ Section Subtitle*') }}</label>
                  <input type="text" class="form-control" name="faq_section_subtitle"
                    value="{{ $data != null ? $data->faq_section_subtitle : '' }}">
                  @if ($errors->has('faq_section_subtitle'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('faq_section_subtitle') }}</p>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="faqSecForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
