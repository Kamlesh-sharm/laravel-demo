@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('General Settings') }}</h4>
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
        <a href="#">{{ __('General Settings') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.basic_settings.general_settings.update') }}" method="post"
          enctype="multipart/form-data">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update General Settings') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <h2 class="mt-3">{{ __('Information') }}</h2>
                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Website Title') . '*' }}</label>
                      <input type="text" class="form-control" name="website_title"
                        value="{{ $data->website_title != null ? $data->website_title : '' }}"
                        placeholder="Enter Website Title">
                      @if ($errors->has('website_title'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('website_title') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Support Email*') }}</label>
                      <input type="email" class="form-control ltr" name="support_email"
                        value="{{ $data->support_email != null ? $data->support_email : '' }}"
                        placeholder="Enter Support Email">
                      @if ($errors->has('support_email'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('support_email') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Support Contact*') }}</label>
                      <input type="text" class="form-control" name="support_contact"
                        value="{{ $data->support_contact != null ? $data->support_contact : '' }}"
                        placeholder="Enter Support Contact">
                      @if ($errors->has('support_contact'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('support_contact') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Address*') }}</label>
                      <input type="text" class="form-control" name="address"
                        value="{{ $data->address != null ? $data->address : '' }}" placeholder="Enter Address">
                      @if ($errors->has('address'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('address') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Latitude') }}</label>
                      <input type="text" class="form-control" name="latitude"
                        value="{{ $data->latitude != null ? $data->latitude : '' }}" placeholder="Enter Latitude">
                      @if ($errors->has('latitude'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('latitude') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>{{ __('Longitude') }}</label>
                      <input type="text" class="form-control" name="longitude"
                        value="{{ $data->longitude != null ? $data->longitude : '' }}" placeholder="Enter Longitude">
                      @if ($errors->has('longitude'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('longitude') }}</p>
                      @endif
                    </div>
                  </div>


                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Favicon') . '*' }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if (!empty($data->favicon))
                          <img src="{{ asset('assets/img/' . $data->favicon) }}" alt="favicon" class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif
                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="favicon">
                        </div>
                      </div>
                      @if ($errors->has('favicon'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('favicon') }}</p>
                      @endif
                      <p class="text-warning mt-2 mb-0">
                        {{ __('Upload 40X40 pixel size image or squre size image for best quality.') }}</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">{{ __('Website Logo') . '*' }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if (!empty($data->logo))
                          <img src="{{ asset('assets/img/' . $data->logo) }}" alt="logo" class="uploaded-img2">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img2">
                        @endif
                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input2" name="logo">
                        </div>
                      </div>
                      @if ($errors->has('logo'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('logo') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8 mx-auto">
                <h2 class="mt-3">{{ __('Preloader') }}</h2>
                <hr>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Status') }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="preloader_status" value="1" class="selectgroup-input"
                            {{ $data->preloader_status == 1 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>
                        <label class="selectgroup-item">
                          <input type="radio" name="preloader_status" value="0" class="selectgroup-input"
                            {{ $data->preloader_status == 0 ? 'checked' : '' }}>
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Preloader') . ' *' }}</label>
                      <br>
                      <div class="thumb-preview" id="thumbPreview1">
                        @if (!empty($data->preloader))
                          <img src="{{ asset('assets/img/' . $data->preloader) }}" alt="Preloader"
                            class="uploaded-img3">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img3">
                        @endif
                      </div>
                      <br>
                      <br>


                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input3" name="preloader">
                        </div>
                      </div>


                      <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, GIF, SVG images are allowed') }}</p>
                      @if ($errors->has('preloader'))
                        <p class="text-danger mb-0">{{ $errors->first('preloader') }}</p>
                      @endif

                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8 mx-auto">
                <h2 class="mt-3">{{ __('Currency') }}</h2>
                <hr>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Base Currency Symbol') . '*' }}</label>
                      <input type="text" class="form-control ltr" name="base_currency_symbol"
                        value="{{ $data->base_currency_symbol }}">
                      @if ($errors->has('base_currency_symbol'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_symbol') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Base Currency Symbol Position') . '*' }}</label>
                      <select name="base_currency_symbol_position" class="form-control ltr">
                        <option selected disabled>{{ __('Select') }}</option>
                        <option value="left" {{ $data->base_currency_symbol_position == 'left' ? 'selected' : '' }}>
                          {{ __('Left') }}</option>
                        <option value="right" {{ $data->base_currency_symbol_position == 'right' ? 'selected' : '' }}>
                          {{ __('Right') }}</option>
                      </select>
                      @if ($errors->has('base_currency_symbol_position'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_symbol_position') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Base Currency Text') . '*' }}</label>
                      <input type="text" class="form-control ltr" name="base_currency_text"
                        value="{{ $data->base_currency_text }}">
                      @if ($errors->has('base_currency_text'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_text') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Base Currency Text Position') . '*' }}</label>
                      <select name="base_currency_text_position" class="form-control ltr">
                        <option selected disabled>{{ __('Select') }}</option>
                        <option value="left" {{ $data->base_currency_text_position == 'left' ? 'selected' : '' }}>
                          {{ __('Left') }}</option>
                        <option value="right" {{ $data->base_currency_text_position == 'right' ? 'selected' : '' }}>
                          {{ __('Right') }}</option>
                      </select>
                      @if ($errors->has('base_currency_text_position'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_text_position') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label>{{ __('Base Currency Rate') . '*' }}</label>
                      <div class="input-group mb-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">{{ __('1 USD =') }}</span>
                        </div>
                        <input type="text" name="base_currency_rate" class="form-control ltr"
                          value="{{ $data->base_currency_rate }}">
                        <div class="input-group-append">
                          <span class="input-group-text">{{ $data->base_currency_text }}</span>
                        </div>
                      </div>
                      @if ($errors->has('base_currency_rate'))
                        <p class="mb-0 text-danger">{{ $errors->first('base_currency_rate') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8 mx-auto">
                <h2 class="mt-3">{{ __('Commission') }}</h2>
                <hr>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Room Booking Commission') }} (%)</label>
                      <input type="number" class="form-control" name="room_booking_commission"
                        value="{{ $commission ? $commission->room_booking_commission : '' }}"
                        placeholder="{{ __('Enter Room Booking Commission') }}">
                      @if ($errors->has('room_booking_commission'))
                        <p class="mb-0 text-danger">{{ $errors->first('room_booking_commission') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">{{ __('Package Booking Commission') }} (%)</label>
                      <input type="number" class="form-control" name="package_booking_commission"
                        value="{{ $commission ? $commission->package_booking_commission : '' }}"
                        placeholder="{{ __('Enter Package Booking Commission') }}">
                      @if ($errors->has('package_booking_commission'))
                        <p class="mb-0 text-danger">{{ $errors->first('package_booking_commission') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-8 mx-auto">
                <h2 class="mt-3">{{ __('Website Appearance') }}</h2>
                <hr>
                <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>{{ __('Primary Color') . '*' }}</label>
                          <input class="jscolor form-control ltr" name="primary_color"
                            value="{{ $data->primary_color }}">
                          @if ($errors->has('primary_color'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('primary_color') }}</p>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>{{ __('Secondary Color') . '*' }}</label>
                          <input class="jscolor form-control ltr" name="secondary_color"
                            value="{{ $data->secondary_color }}">
                          @if ($errors->has('secondary_color'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('secondary_color') }}</p>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>{{ __('Breadcrumb Section Overlay Color') . '*' }}</label>
                          <input class="jscolor form-control ltr" name="breadcrumb_overlay_color"
                            value="{{ $data->breadcrumb_overlay_color }}">
                          @if ($errors->has('breadcrumb_overlay_color'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('breadcrumb_overlay_color') }}</p>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>{{ __('Breadcrumb Section Overlay Opacity') . '*' }}</label>
                          <input class="form-control ltr" type="number" step="0.01"
                            name="breadcrumb_overlay_opacity" value="{{ $data->breadcrumb_overlay_opacity }}">
                          @if ($errors->has('breadcrumb_overlay_opacity'))
                            <p class="mt-2 mb-0 text-danger">{{ $errors->first('breadcrumb_overlay_opacity') }}</p>
                          @endif
                          <p class="mt-2 mb-0 text-warning">
                            {{ __('This will decide the transparency level of the overlay color.') }}<br>
                            {{ __('Value must be between 0 to 1.') }}<br>
                            {{ __('Transparency level will be lower with the increment of the value.') }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
