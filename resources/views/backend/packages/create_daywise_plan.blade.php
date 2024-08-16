<div class="modal fade" id="addDaywisePlanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          {{ __('Add Daywise Plan') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="daywise-plan-ajax-form" class="modal-form"
          action="{{ route('admin.packages_management.store_daywise_plan') }}" method="POST">
          @csrf
          <input type="hidden" value="{{ $language->id }}" name="language_id">
          <input type="hidden" id="package-id-daywise-plan" name="package_id">

          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
            <label for="">{{ __('Day No*') }}</label>
            <input type="number" class="form-control ltr" name="day_number" placeholder="Enter Day Number">
            <p id="err_day_number" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
            <label for="">{{ __('Title') }}</label>
            <input type="text" class="form-control" name="title" placeholder="Enter Plan Title">
          </div>
          <div class="form-group   {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
            <label for="">{{ __('Plan*') }}</label>
            <textarea class="form-control summernote" id="summernote" name="plan" data-height="100"></textarea>
            <p id="err_plan" class="mb-0 text-danger em"></p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button id="daywise-plan-submit-btn" type="button" class="btn btn-primary">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
