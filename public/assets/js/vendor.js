"use strict";
$(".read-more-btn").on("click", function () {
    $(this).prev().toggleClass('show');

    if ($(this).prev().hasClass("show")) {
        $(this).text('Read Less');
    } else {
        $(this).text('Read More');
    }
})
