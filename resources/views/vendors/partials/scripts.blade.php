<script>
  "use strict";

  var mainURL = "{{ url('/') }}";
  var imgupload = "{{ route('admin.summernote.upload') }}";
  var storeURL = "";
  var removeURL = "";
  var rmvdbURL = "";
  var loadImgs = "";
  const baseUrl = "{{ url('/') }}";
</script>
{{-- core js files --}}
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

{{-- axios --}}
<script src="{{ asset('assets/js/axios-0.21.0.min.js') }}"></script>

{{-- jQuery ui --}}
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>

{{-- jQuery time-picker --}}
<script src="{{ asset('assets/js/jquery.timepicker.min.js') }}"></script>

{{-- jQuery scrollbar --}}
<script src="{{ asset('assets/js/jquery.scrollbar.min.js') }}"></script>

{{-- bootstrap notify --}}
<script src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>

{{-- sweet alert --}}
<script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>

{{-- bootstrap tags input --}}
<script src="{{ asset('assets/js/bootstrap-tagsinput.min.js') }}"></script>

{{-- bootstrap date-picker --}}
<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

<!-- Datatable -->
<script src="{{ asset('assets/js/datatables.min.js') }}"></script>

{{-- dropzone js --}}
<script src="{{ asset('assets/js/dropzone.js') }}"></script>

{{-- jQuery dm-uploader js --}}
<script src="{{ asset('assets/js/jquery.dm-uploader.min.js') }}"></script>

{{-- summernote js --}}
<script src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

{{-- js color --}}
<script src="{{ asset('assets/js/jscolor.js') }}"></script>

{{-- atlantis js --}}
<script src="{{ asset('assets/js/atlantis.js') }}"></script>

{{-- fontawesome icon picker js --}}
<script src="{{ asset('assets/js/fontawesome-iconpicker.min.js') }}"></script>

{{-- fonts and icons script --}}
<script src="{{ asset('assets/js/webfont.min.js') }}"></script>

{{-- functions js --}}
<script src="{{ asset('assets/js/functions.js') }}"></script>

{{-- misc js --}}
<script src="{{ asset('assets/js/misc.js') }}"></script>

{{-- moment js --}}
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>

{{-- date-range-picker js --}}
<script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>

@if (session()->has('success'))
  <script>
    "use strict";
    var content = {};

    content.message = '{{ session('success') }}';
    content.title = 'Success';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'success',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif

@if (session()->has('warning'))
  <script>
    "use strict";
    var content = {};

    content.message = '{{ session('warning') }}';
    content.title = 'Warning!';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'warning',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif

@if (session()->has('error'))
  <script>
    "use strict";
    var content = {};

    content.message = '{{ session('error') }}';
    content.title = 'Error!';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'danger',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif

{{-- select2 js --}}
<script type="text/javascript" src="{{ asset('assets/js/select2.min.js') }}"></script>

<script>
  var account_status = {{ Auth::guard('vendor')->user()->status }};
</script>
@if (session()->has('secret_login'))
  <script>
    var secret_login = {{ Session::get('secret_login') }};
  </script>
@else
  <script>
    var secret_login = 0;
  </script>
@endif

{{-- admin-main js --}}
<script src="{{ asset('assets/js/admin-main.js') }}"></script>
