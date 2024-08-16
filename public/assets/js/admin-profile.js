(function ($) {
    "use strict";

    $('.upload').on('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {
          $('.user-photo').attr('src', e.target.result);
        };

        reader.readAsDataURL(file);
    });

})(jQuery);
  