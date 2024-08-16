@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl_style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Announcement Popup') }}</h4>
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
        <a href="#">{{ __('Announcement Popup') }}</a>
      </li>
    </ul>
  </div>
  @php
    $type = $popup->type;
  @endphp
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Add Popup') }} ({{ __('Type') }} - {{ $type }})</div>
            </div>
            <div class="col-lg-2 text-right">
              <a href="{{ route('admin.popup.index') . '?language=' . request()->input('language') }}"
                class="btn btn-primary btn-sm">{{ __('Back') }}</a>
            </div>
          </div>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">

              <form id="ajaxForm" class="modal-form" action="{{ route('admin.popup.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="popup_id" value="{{ $popup->id }}">
                <input type="hidden" name="type" value="{{ $type }}">

                @if ($type == 1 || $type == 4 || $type == 5 || $type == 7)
                  {{-- Image Part --}}
                  <div class="form-group">
                    <label for="">{{ __('Image') }} ** </label>
                    <br>
                    <div class="thumb-preview" id="thumbPreview1">
                      @if (!empty($popup->image))
                        <img src="{{ asset('assets/img/popups/' . $popup->image) }}" alt="image" class="uploaded-img">
                      @else
                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                      @endif
                    </div>

                    <br><br>

                    <div class="mt-3">
                      <div role="button" class="btn btn-primary btn-sm upload-btn">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="image">
                      </div>
                    </div>


                    <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                    <p class="em text-danger mb-0" id="err_image"></p>

                  </div>
                @endif

                @if ($type == 2 || $type == 3 || $type == 6)
                  <div class="form-group">
                    <label for="">{{ __('Background Image') }} ** </label>
                    <br>
                    <div class="thumb-preview" id="thumbPreview1">
                      @if (!empty($popup->background_image))
                        <img src="{{ asset('assets/img/popups/' . $popup->background_image) }}" alt="image"
                          class="uploaded-img">
                      @else
                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                      @endif
                    </div>

                    <br><br>

                    <div class="mt-3">
                      <div role="button" class="btn btn-primary btn-sm upload-btn">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="background_image">
                      </div>
                    </div>


                    <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
                    <p class="em text-danger mb-0" id="err_image"></p>

                  </div>
                @endif

                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Popup Name') }} **</label>
                      <input type="text" class="form-control" name="name" value="{{ $popup->name }}"
                        placeholder="Enter Name">
                      <p class="text-warning mb-0">
                        {{ __('This will not be shown in the popup in Website, it will help you to indentify the popup in Admin Panel.') }}
                      </p>
                      <p id="err_name" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>


                @if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="">{{ __('Title') }} </label>
                        <input type="text" class="form-control" name="title" value="{{ $popup->title }}"
                          placeholder="Enter Title">
                        <p id="err_title" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="">{{ __('Text') }} </label>
                        <textarea class="form-control" name="text" cols="30" rows="3" placeholder="Enter Text">{{ $popup->text }}</textarea>
                        <p id="err_text" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($type == 6 || $type == 7)
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('End Date') }} **</label>
                        <input type="text" class="form-control ltr datepicker" name="end_date"
                          value="{{ $popup->end_date }}" placeholder="Enter End Date" autocomplete="off">
                        <p id="err_end_date" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('End Time') }} **</label>
                        <input type="text" class="form-control ltr timepicker" name="end_time"
                          value="{{ $popup->end_time }}" placeholder="Enter End Time" autocomplete="off">
                        <p id="err_end_time" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($type == 2 || $type == 3)
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Background Color Code') }} **</label>
                        <input class="jscolor form-control ltr" name="background_color"
                          value="{{ $popup->background_color }}">
                        <p class="em text-danger mb-0" id="err_background_color"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Background Color Opacity') }} **</label>
                        <input type="number" class="form-control ltr" name="background_opacity"
                          value="{{ $popup->background_opacity }}" placeholder="Enter Opacity Value">
                        <p id="err_background_opacity" class="mb-0 text-danger em"></p>
                        <ul class="mb-0">
                          <li class="text-warning mb-0">{{ __('Value must be between 0 to 1') }}</li>
                          <li class="text-warning mb-0">
                            {{ __('The more the opacity value is, the less the trnsparency level will be.') }}</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($type == 7)
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>{{ __('Background Color Code') }} **</label>
                        <input class="jscolor form-control ltr" name="background_color"
                          value="{{ $popup->background_color }}">
                        <p class="em text-danger mb-0" id="err_background_color"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6 || $type == 7)
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Button Text') }} </label>
                        <input type="text" class="form-control" name="button_text"
                          value="{{ $popup->button_text }}" placeholder="Enter Button Text">
                        <p id="err_button_text" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="">{{ __('Button Color') }} </label>
                        <input type="text" class="form-control jscolor ltr" name="button_color"
                          value="{{ $popup->button_color }}" placeholder="Enter Button Color">
                        <p id="err_button_color" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($type == 2 || $type == 4 || $type == 6 || $type == 7)
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="">{{ __('Button URL') }} </label>
                        <input type="text" class="form-control ltr" name="button_url"
                          value="{{ $popup->button_url }}" placeholder="Enter Button URL">
                        <p id="err_button_url" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                <div class="form-group">
                  <label for="">{{ __('Delay (miliseconds)') }} **</label>
                  <input type="number" class="form-control ltr" name="delay" value="{{ $popup->delay }}"
                    placeholder="Enter Serial Number">
                  <p id="err_delay" class="mb-0 text-danger em"></p>
                  <p class="text-warning mb-0">{{ __('This will decide the delay time to show the popup') }}</p>
                </div>
                <div class="form-group">
                  <label for="">{{ __('Serial Number') }} **</label>
                  <input type="number" class="form-control ltr" name="serial_number"
                    value="{{ $popup->serial_number }}" placeholder="Enter Serial Number">
                  <p id="err_serial_number" class="mb-0 text-danger em"></p>
                  <ul>
                    <li class="text-warning mb-0">{{ __('If there are') }} <strong
                        class="text-info">{{ __('Multiple Active
                                                                                                                                                                                                                                                                                                                                                                                                                                                Popups') }}</strong>,
                      {{ __('then the popups will be shown in the website according to') }} <strong
                        class="text-info">{{ __('Serial Number') }}</strong></li>
                    <li class="text-warning">
                      {{ __('The higher the serial number, the later the popups will be visible in Website') }}</li>
                  </ul>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form-group from-show-notify row">
            <div class="col-12 text-center">
              <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
