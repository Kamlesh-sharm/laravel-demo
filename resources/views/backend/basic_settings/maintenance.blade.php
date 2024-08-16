@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Maintenance Mode') }}</h4>
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
        <a href="#">{{ __('Maintenance Mode') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Update Maintenance Mode') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="maintenanceForm" action="{{ route('admin.basic_settings.update_maintenance') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <div class="thumb-preview" id="thumbPreview1">
                    @if (!empty($data->maintenance_img))
                      <img src="{{ asset('assets/img/' . $data->maintenance_img) }}" alt="image" class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @endif
                  </div>

                  <br><br>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="maintenance_img">
                    </div>
                  </div>


                  @if ($errors->has('maintenance_img'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('maintenance_img') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Maintenance Status*') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="maintenance_status" value="1" class="selectgroup-input"
                        {{ $data->maintenance_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="maintenance_status" value="0" class="selectgroup-input"
                        {{ $data->maintenance_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('maintenance_status'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('maintenance_status') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Maintenance Message*') }}</label>
                  <textarea class="form-control" name="maintenance_msg" rows="3" cols="80">{{ $data->maintenance_msg }}</textarea>
                  @if ($errors->has('maintenance_msg'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('maintenance_msg') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Secret Path') }}</label>
                  <input name="secret_path" type="text" class="form-control" value="{{ $data->secret_path }}">
                  <p class="text-warning mt-1 mb-0">
                    {{ __('After activating maintenance mode, You can access the website via') }} <strong
                      class="text-danger">{{ url('{secret_path}') }}</strong>
                  </p>
                  <p class="text-warning mb-0">{{ __('Try to avoid using special characters.') }}</p>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="maintenanceForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
