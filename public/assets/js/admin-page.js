(function ($) {
    "use strict";

    $("#pageForm").on('submit', function (e) {
        $(".request-loader").addClass('show');
        e.preventDefault();
        let action = $("#pageForm").attr('action');
        let fd = new FormData(document.querySelector("#pageForm"));

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
                $(".request-loader").removeClass('show');
                if (data == "success") {
                    location.reload(true);
                } else if (typeof data.error != 'undefined') {
                    $("#pageErrors").show();
                    let errors = ``;

                    for (let x in data) {
                        if (x == 'error') {
                            continue;
                        }
                        errors += `<li><p class="text-danger mb-0">${data[x]}</p></li>`;
                    }
                    $("#pageErrors ul").html(errors);

                    $("html, body").animate({ scrollTop: $('#pageErrors').offset().top - 100 }, 1000);
                }
            }
        })
    });


})(jQuery);
