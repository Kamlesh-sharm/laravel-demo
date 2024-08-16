function cloneContent(fromId, toId, event) {
  "use strict";
  let $target = $(event.target);

  if ($target.is(":checked")) {
    $("#" + fromId + " .form-control").each(function (i) {
      let index = i;
      let val = $(this).val();
      let $toInput = $("#" + toId + " .form-control").eq(index);

      if ($(this).hasClass('summernote')) {
        let val = tinyMCE.activeEditor.getContent();
        let tmcId = $toInput.attr('id');
        tinyMCE.get(tmcId).setContent(val);
      } else if ($(this).data('role') == 'tagsinput') {
        if (val.length > 0) {
          let tags = val.split(',');
          tags.forEach(tag => {
            $toInput.tagsinput('add', tag);
          });
        } else {
          $toInput.tagsinput('removeAll');
        }
      } else {
        $toInput.val(val);
      }
    });
  } else {
    $("#" + toId + " .form-control").each(function (i) {
      let $toInput = $("#" + toId + " .form-control").eq(i);
      if ($(this).hasClass('summernote')) {
        $toInput.summernote('code', '');
      } else if ($(this).data('role') == 'tagsinput') {
        $toInput.tagsinput('removeAll');
      } else {
        $toInput.val('');
      }
    });
  }
}


function bootnotify(message, title, type) {
  "use strict";
  var content = {};

  content.message = message;
  content.title = title;
  content.icon = 'fa fa-bell';

  $.notify(content, {
    type: type,
    placement: {
      from: 'top',
      align: 'right'
    },
    showProgressbar: true,
    time: 1000,
    allow_dismiss: true,
    delay: 4000,
  });
}
