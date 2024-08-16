(function ($) {
  "use strict";
  // show package plan text according to plan type
  $(document).on('change', 'select[name="plan_type"]', function (e) {
    var selectOptVal = $(this).val();

    if (selectOptVal === 'daywise') {
      $('#daywise-text').removeClass('d-none');
    } else {
      $('#daywise-text').addClass('d-none');
    }

    if (selectOptVal === 'timewise') {
      $('#timewise-text').removeClass('d-none');
    } else {
      $('#timewise-text').addClass('d-none');
    }
  });

  // show package price input field according to price type
  $(document).on('click', 'input:radio[name="pricing_type"]:checked', function (e) {
    var radioBtnVal = $(this).val();

    if (radioBtnVal === 'fixed') {
      $('#fixed-price').removeClass('d-none');
    } else {
      $('#fixed-price').addClass('d-none');
    }

    if (radioBtnVal === 'per-person') {
      $('#per-person-price').removeClass('d-none');
    } else {
      $('#per-person-price').addClass('d-none');
    }
  });


  $('#packageForm').on('submit', function (e) {
    $('.request-loader').addClass('show');
    e.preventDefault();

    let action = $('#packageForm').attr('action');
    let fd = new FormData(document.querySelector('#packageForm'));

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
        $('#packageErrors').show();
        let errors = ``;

        for (let x in error.responseJSON.errors) {
          errors += `<li>
                <p class="text-danger mb-0">${error.responseJSON.errors[x][0]}</p>
              </li>`;
        }

        $('#packageErrors ul').html(errors);

        $('.request-loader').removeClass('show');

        $('html, body').animate({
          scrollTop: $('#packageErrors').offset().top - 100
        }, 1000);
      }
    });
  });


  // on page load, this block of code will execute
  $(window).on('load', function () {
    // show text for selected plan type
    var selectedValue = $('select[name="plan_type"]').val();

    if (selectedValue === 'daywise') {
      $('#daywise-text').removeClass('d-none');
    } else if (selectedValue === 'timewise') {
      $('#timewise-text').removeClass('d-none');
    }

    // show input field for checked pricing type
    var checkedValue = $('input:radio[name="pricing_type"]:checked').val();

    if (checkedValue === 'fixed') {
      $('#fixed-price').removeClass('d-none');
    } else if (checkedValue === 'per-person') {
      $('#per-person-price').removeClass('d-none');
    }
  });


  // show package plan text according to plan type
  $(document).on('change', 'select[name="plan_type"]', function (e) {
    var selectOptVal = $(this).val();

    if (selectOptVal === 'daywise') {
      $('#daywise-text').removeClass('d-none');
    } else {
      $('#daywise-text').addClass('d-none');
    }

    if (selectOptVal === 'timewise') {
      $('#timewise-text').removeClass('d-none');
    } else {
      $('#timewise-text').addClass('d-none');
    }
  });


  // show package price input field according to price type
  $(document).on('click', 'input:radio[name="pricing_type"]:checked', function (e) {
    var radioBtnVal = $(this).val();

    if (radioBtnVal === 'fixed') {
      $('#fixed-price').removeClass('d-none');
    } else {
      $('#fixed-price').addClass('d-none');
    }

    if (radioBtnVal === 'per-person') {
      $('#per-person-price').removeClass('d-none');
    } else {
      $('#per-person-price').addClass('d-none');
    }
  });

})(jQuery);
