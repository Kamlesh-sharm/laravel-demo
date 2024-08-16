@if ($language->direction == 1)
  @section('style')
    <style>
      form input, form textarea, select, form select {
        direction: rtl;
      }

      form .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
      }
    </style>
  @endsection
@endif
