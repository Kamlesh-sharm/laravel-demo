(function ($) {
  "use strict";

  // initialize date range picker
  $('#date-range').daterangepicker({
    minDate: new Date(),
    opens: 'left',
    autoUpdateInput: false,
  });


  // show the dates in input field when user select a date range
  $('#date-range').on('apply.daterangepicker', function (event, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
  });

  // remove the dates when user click on cancel button
  $('#date-range').on('cancel.daterangepicker', function (event, picker) {
    $(this).val('');
  });

})(jQuery);
