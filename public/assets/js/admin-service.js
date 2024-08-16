(function ($) {
    "use strict";
        let status = $("input[name='details_page_status']:checked").val();

        if (status == 0) {
        $('.service-content').addClass('d-none');
        } else {
        $('.service-content').removeClass('d-none');
        }
        
      // toggle the content field by changing the details page status option
      $("input[type='radio']").on('click', function() {
        let radioValue = $("input[name='details_page_status']:checked").val();

        if (radioValue == 0) {
          $('.service-content').addClass('d-none');
        } else {
          $('.service-content').removeClass('d-none');
        }
      });


      // submit the service form using ajax
      $('#serviceForm').on('submit', function(e) {
        $('.request-loader').addClass('show');
        e.preventDefault();

        if ($('.iconpicker-component').length > 0) {
          $('#inputIcon').val($('.iconpicker-component').find('i').attr('class'));
        }

        let action = $('#serviceForm').attr('action');
        let fd = new FormData(document.querySelector('#serviceForm'));

        $.ajax({
          url: action,
          method: 'POST',
          data: fd,
          contentType: false,
          processData: false,
          success: function(data) {
            $('.request-loader').removeClass('show');

            if (data == 'success') {
              location.reload(true);
            }
          },
          error: function(error) {
            $('#serviceErrors').show();
            let errors = ``;

            for (let x in error.responseJSON.errors) {
              errors += `<li>
                <p class="text-danger mb-0">${ error.responseJSON.errors[x][0] }</p>
              </li>`;
            }

            $('#serviceErrors ul').html(errors);

            $('.request-loader').removeClass('show');

            $('html, body').animate({
              scrollTop: $('#serviceErrors').offset().top - 100
            }, 1000);
          }
        });
      });
  
   
  })(jQuery);
  