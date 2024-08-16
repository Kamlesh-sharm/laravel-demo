<div class="modal fade" id="editDaywisePlanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          {{ __('Update Daywise Plan') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="ajaxEditForm" class="modal-form" action="{{ route('admin.packages_management.update_daywise_plan') }}"
          method="POST">
          @csrf
          <input type="hidden" id="in_id" name="plan_id">

          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
            <label for="">{{ __('Day Number*') }}</label>
            <input type="number" id="in_day_number" class="form-control ltr" name="day_number"
              placeholder="Enter Day Number">
            <p id="editErr_day_number" class="mt-1 mb-0 text-danger em"></p>
          </div>

          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
            <label for="">{{ __('Title') }}</label>
            <input type="text" id="in_title" class="form-control" name="title" placeholder="Enter Plan Title">
          </div>

          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
            <label for="">{{ __('Plan*') }}</label>
            <textarea id="in_edit_plan" class="form-control summernote" name="edit_plan" data-height="100"></textarea>
            <p id="editErr_edit_plan" class="mb-0 text-danger em"></p>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button id="updateBtn" type="button" class="btn btn-primary">
          {{ __('Update') }}
        </button>
      </div>
    </div>
  </div>
</div>
