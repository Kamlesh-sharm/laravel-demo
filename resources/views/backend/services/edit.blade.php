@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Service') }}</h4>
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
        <a href="#">{{ __('Services Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Service') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Update Service') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.services_management') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1" id="serviceErrors" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>

              <form id="serviceForm"
                action="{{ route('admin.services_management.update_service', ['id' => $service->id]) }}" method="POST">
                @csrf
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">{{ __('Service\'s Icon*') }}</label>
                      <div class="btn-group d-block">
                        <button type="button" class="btn btn-primary iconpicker-component"><i
                            class="{{ $service->service_icon }}"></i></button>
                        <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle" data-selected="fa-car"
                          data-toggle="dropdown"></button>
                        <div class="dropdown-menu"></div>
                      </div>
                      <input type="hidden" id="inputIcon" name="service_icon">
                      <div class="text-warning mt-2">
                        <small>{{ __('Click on the dropdown icon to select a icon.') }}</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Details Page*') }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="details_page_status" value="1" class="selectgroup-input"
                            {{ $service->details_page_status == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Enable') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="details_page_status" value="0" class="selectgroup-input"
                            {{ $service->details_page_status == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Disable') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Serial Number*') }}</label>
                      <input type="number" class="form-control" name="serial_number" placeholder="Enter Serial Number"
                        value="{{ $service->serial_number }}">
                      <p class="text-warning mt-2">
                        <small>{{ __('The higher the serial number is, the later the service will be shown.') }}</small>
                      </p>
                    </div>
                  </div>
                </div>

                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    @php
                      $serviceContent = $language
                          ->serviceDetails()
                          ->where('service_id', $service->id)
                          ->first();
                      $title = !empty($serviceContent) ? $serviceContent->title : '';
                      $summary = !empty($serviceContent) ? $serviceContent->summary : '';
                      $content = !empty($serviceContent) ? $serviceContent->content : '';
                      $meta_keywords = !empty($serviceContent) ? $serviceContent->meta_keywords : '';
                      $meta_description = !empty($serviceContent) ? $serviceContent->meta_description : '';
                    @endphp

                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }} {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title*') }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="Enter Title" value="{{ $title }}">
                              </div>
                            </div>

                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Summary*') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_summary" placeholder="Enter Summary" rows="3">{{ $summary }}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row service-content">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Content*') }}</label>
                                <textarea class="form-control summernote" id="{{ $language->code }}_content" name="{{ $language->code }}_content"
                                  placeholder="Enter Content" data-height="300">{{ replaceBaseUrl($content, 'summernote') }}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="Enter Meta Keywords" data-role="tagsinput"
                                  value="{{ $meta_keywords }}">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="Enter Meta Description">{{ $meta_description }}</textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="serviceForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script src="{{ asset('assets/js/admin-service.js') }}"></script>
@endsection
