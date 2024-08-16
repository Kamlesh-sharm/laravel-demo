@extends('backend.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">{{ __('Announcement Popup') }}</h4>
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

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-lg-12">
            <div class="card-title">{{ __('Update Announcement Popup') }}</div>
          </div>
        </div>
      </div>

      <div class="card-body pt-5 pb-5">
        <div class="row">
          <div class="col-lg-6 offset-lg-3">
            <form id="announcementForm" action="{{ route('admin.basic_settings.update_announcement') }}" method="POST">
              @csrf
              <div class="form-group">
                <div class="thumb-preview" id="thumbPreview1">
                  @if (!empty($data->announcement_img))
                    <img src="{{ asset('assets/img/' . $data->announcement_img) }}" alt="announcement image">
                  @else
                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="...">
                  @endif
                </div>
                <br><br>

                <input type="hidden" id="fileInput1" name="announcement_img">
                <button 
                  id="chooseImage1" 
                  class="choose-image btn btn-primary" 
                  type="button"  
                >{{ __('Choose Image') }}</button>
                @if ($errors->has('announcement_img'))
                  <p class="mt-2 mb-0 text-danger">{{ $errors->first('announcement_img') }}</p>
                @endif 
              </div>

              <div class="form-group">
                <label>{{ __('Announcement Popup Status') }}</label>
                <div class="selectgroup w-100">
                  <label class="selectgroup-item">
                    <input 
                      type="radio" 
                      name="announcement_status" 
                      value="1" 
                      class="selectgroup-input" 
                      {{ $data->announcement_status == 1 ? 'checked' : '' }}
                    >
                    <span class="selectgroup-button">{{ __('Active') }}</span>
                  </label>
                  <label class="selectgroup-item">
                    <input 
                      type="radio" 
                      name="announcement_status" 
                      value="0" 
                      class="selectgroup-input" 
                      {{ $data->announcement_status == 0 ? 'checked' : '' }}
                    >
                    <span class="selectgroup-button">{{ __('Deactive') }}</span>
                  </label>
                </div>
                @if ($errors->has('announcement_status'))
                  <p class="mt-2 mb-0 text-danger">{{ $errors->first('announcement_status') }}</p>
                @endif
              </div>

              <div class="form-group">
                <label>{{ __('Popup Delay (second)') }}</label>
                <input type="number" step="0.01" class="form-control" name="popup_delay" value="{{ $data->popup_delay }}">
                @if ($errors->has('popup_delay'))
                  <p class="mt-2 mb-0 text-danger">{{ $errors->first('popup_delay') }}</p>
                @endif
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="card-footer">
        <div class="row">
          <div class="col-12 text-center">
            <button type="submit" form="announcementForm" class="btn btn-success">
              {{ __('Update') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
