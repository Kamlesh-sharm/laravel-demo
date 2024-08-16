<!-- Create Gallery Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Create User') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form id="ajaxForm" class="" action="{{ route('admin.user.store') }}" method="POST"
          enctype="multipart/form-data">
          @csrf
          {{-- Image Part --}}
          <div class="form-group">
            <label for="">{{ __('Image') }} ** </label>
            <br>
            <div class="thumb-preview">
              <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
            </div>
            <br><br>

            <div class="mt-3">
              <div role="button" class="btn btn-primary btn-sm upload-btn">
                {{ __('Choose Image') }}
                <input type="file" class="img-input" name="image">
              </div>
            </div>

            <input id="fileInput1" type="hidden" name="image">


            <p class="text-warning mb-0">{{ __('JPG, PNG, JPEG, SVG images are allowed') }}</p>
            <p class="em text-danger mb-0" id="err_image"></p>

          </div>


          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Username') }} **</label>
                <input type="text" class="form-control" name="username" placeholder="Enter username" value="">
                <p id="err_username" class="mb-0 text-danger em"></p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Email') }} **</label>
                <input type="text" class="form-control" name="email" placeholder="Enter email" value="">
                <p id="err_email" class="mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('First Name') }} **</label>
                <input type="text" class="form-control" name="first_name" placeholder="Enter first name"
                  value="">
                <p id="err_first_name" class="mb-0 text-danger em"></p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Last Name') }} **</label>
                <input type="text" class="form-control" name="last_name" placeholder="Enter last name"
                  value="">
                <p id="err_last_name" class="mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Password') }} **</label>
                <input type="password" class="form-control" name="password" placeholder="Enter password" value="">
                <p id="err_password" class="mb-0 text-danger em"></p>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">{{ __('Re-type Password') }} **</label>
                <input type="password" class="form-control" name="password_confirmation"
                  placeholder="Enter your password again" value="">
                <p id="err_password_confirmation" class="mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label for="">{{ __('Role') }} **</label>
                <select class="form-control" name="role">
                  <option value="" selected disabled>{{ __('Select a Role') }}</option>
                  @foreach ($roles as $key => $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                  @endforeach
                </select>
                <p id="err_role" class="mb-0 text-danger em"></p>
              </div>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
        <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
      </div>
    </div>
  </div>
</div>
