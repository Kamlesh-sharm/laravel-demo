<div
  class="modal fade"
  id="addLocationModal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          {{ __('Add Location') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form
          id="ajaxForm"
          class="modal-form"
          action="{{ route('admin.packages_management.store_location') }}"
          method="POST"
        >
          @csrf

          <input type="hidden" name="language_id" value="{{$language->id}}">
          <input type="hidden" id="package-id-location" name="package_id">

          <div class="form-group">
            <label for="">{{ __('Lacation Name*') }}</label>
            <input type="text" class="form-control" name="name" placeholder="Enter Location Name">
            <p id="err_name" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Latitude') }}</label>
            <input type="number" step="0.01" class="form-control ltr" name="latitude" placeholder="Enter Location Latitude">
            <p class="mt-2 mb-0 text-warning">
              {{ __('The value of the latitude will be helpful to show the place in the map.') }}
            </p>
          </div>

          <div class="form-group">
            <label for="">{{ __('Longitude') }}</label>
            <input type="number" step="0.01" class="form-control ltr" name="longitude" placeholder="Enter Location Longitude">
            <p class="mt-2 mb-0 text-warning">
              {{ __('The value of the longitude will be helpful to show the place in the map.') }}
            </p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button id="submitBtn" type="button" class="btn btn-primary">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
