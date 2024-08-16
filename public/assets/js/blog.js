(function ($) {
    "use strict";
  
    $(document).on('click', '#selectCategory', function (event) {
        event.preventDefault();
    
        let id = $(this).attr('data-id');
    
        $('#selectKey').val(id);
        $('#submitBtn').trigger('click');
    });

    var d = document, s = d.createElement('script');
    s.src = 'https://' + shortName + '.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
   
  })(jQuery);
  