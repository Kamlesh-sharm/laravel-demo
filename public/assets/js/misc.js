(function ($) {
  "use strict";

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $('#blogForm').on('submit', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();

    let action = $('#blogForm').attr('action');
    let fd = new FormData(document.querySelector('#blogForm'));

    //if summernote has then get summernote content
    $('.form-control').each(function (i) {
      let index = i;

      let $toInput = $('.form-control').eq(index);

      if ($(this).hasClass('summernote')) {
        let tmcId = $toInput.attr('id');
        let content = tinyMCE.get(tmcId).getContent();
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      }
    });

    $.ajax({
      url: action,
      method: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $('.request-loader').removeClass('show');

        if (data == 'success') {
          location.reload(true);
        }
      },
      error: function (error) {
        $('#blogErrors').addClass('d-block');
        $('#blogErrors').removeClass('d-none');
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
          <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
        </li>`;
        }

        $('#blogErrors ul').html(errors);

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#blogErrors').offset().top - 100
        }, 1000);
      }
    });
  });

  $('#socialForm').on('submit', function (e) {
    e.preventDefault();

    $('#inputIcon').val($('.iconpicker-component').find('i').attr('class'));
    document.getElementById('socialForm').submit();
  });
})(jQuery);
