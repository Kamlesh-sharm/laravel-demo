@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Social Links') }}</h4>
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
        <a href="#">{{ __('Social Links') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form
          id="socialForm"
          action="{{ route('admin.basic_settings.update_social_link') }}"
          method="post"
        >
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Edit Social Link') }}</div>
            <a
              class="btn btn-info btn-sm float-right d-inline-block"
              href="{{ route('admin.basic_settings.social_links') }}"
            >
              <span class="btn-label">
                <i class="fas fa-backward"></i>
              </span>
              {{ __('Back') }}
            </a>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                @csrf
                <input type="hidden" name="id" value="{{ $socialLink->id }}">
                <div class="form-group">
                  <label for="">{{ __('Social Icon*') }}</label>
                  <div class="btn-group d-block">
                    <button
                      type="button"
                      class="btn btn-primary iconpicker-component"
                    ><i class="{{ $socialLink->icon }}"></i></button>
                    <button
                      type="button"
                      class="icp icp-dd btn btn-primary dropdown-toggle"
                      data-selected="fa-car"
                      data-toggle="dropdown"
                    ></button>
                    <div class="dropdown-menu"></div>
                  </div>
                  <input type="hidden" id="inputIcon" name="icon">
                  @if ($errors->has('icon'))
                    <p class="mb-0 text-danger">{{ $errors->first('icon') }}</p>
                  @endif
                  <div class="text-warning mt-2">
                    <small>{{ __('Click on the dropdown icon to select a social link icon.') }}</small>
                  </div>
                </div>

                <div class="form-group">
                  <label for="">{{ __('URL*') }}</label>
                  <input
                    type="url"
                    class="form-control"
                    name="url"
                    value="{{ $socialLink->url }}"
                    placeholder="Enter URL of Social Media Account"
                  >
                  @if ($errors->has('url'))
                    <p class="mb-0 text-danger">{{ $errors->first('url') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label for="">{{ __('Serial Number*') }}</label>
                  <input
                    type="number"
                    class="form-control ltr"
                    name="serial_number"
                    value="{{ $socialLink->serial_number }}"
                    placeholder="Enter Serial Number"
                  >
                  @if ($errors->has('serial_number'))
                    <p class="mb-0 text-danger">{{ $errors->first('serial_number') }}</p>
                  @endif
                  <p class="text-warning mt-2">
                    <small>{{ __('The higher the serial number is, the later the social link will be shown.') }}</small>
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer pt-3">
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
