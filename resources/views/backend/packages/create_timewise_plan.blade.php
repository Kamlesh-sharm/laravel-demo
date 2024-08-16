<div
  class="modal fade"
  id="addTimewisePlanModal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          {{ __('Add Timewise Plan') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form
          id="timewise-plan-ajax-form"
          class="modal-form"
          action="{{ route('admin.packages_management.store_timewise_plan') }}"
          method="POST"
        >
          @csrf
          <input type="hidden" value="{{$language->id}}" name="language_id">
          <input type="hidden" id="package-id-timewise-plan" name="package_id">

          <div class="form-group">
            <label for="">{{ __('Start Time*') }}</label>
            <input type="text" class="form-control ltr timepicker" name="start_time" placeholder="11:00 AM" data-interval="30" data-start="10:00am" autocomplete="off">
            <p id="err_start_time" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group">
            <label for="">{{ __('End Time*') }}</label>
            <input type="text" class="form-control ltr timepicker" name="end_time" placeholder="12:00 PM" data-interval="30" data-start="10:00am" autocomplete="off">
            <p id="err_end_time" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
            <label for="">{{ __('Title') }}</label>
            <input type="text" class="form-control" name="title" placeholder="Enter Plan Title">
          </div>

          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
            <label for="">{{ __('Plan*') }}</label>
            <textarea class="form-control summernote" id="timewisePlanSummernote" name="plan" data-height="100" ></textarea>
            <p id="err_plan" class="mb-0 text-danger em"></p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button id="timewise-plan-submit-btn" type="button" class="btn btn-primary">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
