@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit User') }}</h4>
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
        <a href="#">{{ __('User Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit User') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit User') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.user.index') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">

              <form id="ajaxForm" class="" action="{{ route('admin.user.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="user_id" value="{{ $user->id }}">

                {{-- Image Part --}}
                <div class="form-group">
                  <label for="">{{ __('Image') }} ** </label>
                  <br>

                  <div class="thumb-preview" id="thumbPreview1">
                    @if (!empty($user->image))
                      <img src="{{ asset('assets/img/admins/' . $user->image) }}" alt="image" class="uploaded-img">
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

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Username') }} **</label>
                      <input type="text" class="form-control" name="username" placeholder="Enter username"
                        value="{{ $user->username }}">
                      <p id="err_username" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Email') }} **</label>
                      <input type="text" class="form-control" name="email" placeholder="Enter email"
                        value="{{ $user->email }}">
                      <p id="err_email" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('First Name') }} **</label>
                      <input type="text" class="form-control" name="first_name" placeholder="Enter first name"
                        value="{{ $user->first_name }}">
                      <p id="err_first_name" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Last Name') }} **</label>
                      <input type="text" class="form-control" name="last_name" placeholder="Enter last name"
                        value="{{ $user->last_name }}">
                      <p id="err_last_name" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Status') }} **</label>
                      <select class="form-control" name="status">
                        <option value="" selected disabled>{{ __('Select a status') }}</option>
                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>{{ __('Deactive') }}</option>
                      </select>
                      <p id="err_status" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Role') }} **</label>
                      <select class="form-control" name="role">
                        <option value="" selected disabled>{{ __('Select a Role') }}</option>
                        @foreach ($roles as $key => $role)
                          <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}</option>
                        @endforeach
                      </select>
                      <p id="err_role" class="mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
