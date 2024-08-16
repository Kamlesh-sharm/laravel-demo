'use strict';

function popupAnnouncement($this) {
  let closedPopups = [];
  if (sessionStorage.getItem('closedPopups')) {
    closedPopups = JSON.parse(sessionStorage.getItem('closedPopups'));
  }

  // if the popup is not in closedPopups Array
  if (closedPopups.indexOf($this.data('popup_id')) == -1) {
    $('#' + $this.attr('id')).show();
    let popupDelay = $this.data('popup_delay');

    setTimeout(function () {
      jQuery.magnificPopup.open({
        items: { src: '#' + $this.attr('id') },
        type: 'inline',
        callbacks: {
          afterClose: function () {
            // after the popup is closed, store it in the sessionStorage & show next popup
            closedPopups.push($this.data('popup_id'));
            sessionStorage.setItem('closedPopups', JSON.stringify(closedPopups));


            if ($this.next('.popup-wrapper').length > 0) {
              popupAnnouncement($this.next('.popup-wrapper'));
            }
          }
        }
      }, 0);
    }, popupDelay);
  } else {
    if ($this.next('.popup-wrapper').length > 0) {
      popupAnnouncement($this.next('.popup-wrapper'));
    }
  }
}
$(function () {

  $('#dashboard-datatable').DataTable({
    responsive: true,
    ordering: false
  });

  $('.offer-timer').each(function () {
    let $this = $(this);
    let d = new Date($this.data('end_date'));
    let ye = parseInt(new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d));
    let mo = parseInt(new Intl.DateTimeFormat('en', { month: 'numeric' }).format(d));
    let da = parseInt(new Intl.DateTimeFormat('en', { day: '2-digit' }).format(d));
    let t = $this.data('end_time');
    let time = t.split(":");
    let hr = parseInt(time[0]);
    let min = parseInt(time[1]);
    $this.syotimer({
      year: ye,
      month: mo,
      day: da,
      hour: hr,
      minute: min,
    });
  });



  // Sticky Menu
  $(window).on('scroll', function (event) {
    var scroll = $(window).scrollTop();

    if (scroll < 250) {
      $('.header-menu-area').removeClass('sticky');
      $('header.home-two .container-fluid').removeClass('sticky');
    } else {
      $('.header-menu-area').addClass('sticky');
      $('header.home-two .container-fluid').addClass('sticky');
    }
  });

  // subscribe functionality
  if ($(".subscribeForm").length > 0) {
    $(".subscribeForm").each(function () {
      let $this = $(this);

      $this.on('submit', function (e) {

        e.preventDefault();

        let formId = $this.attr('id');
        let fd = new FormData(document.getElementById(formId));

        $.ajax({
          url: $this.attr('action'),
          type: $this.attr('method'),
          data: fd,
          contentType: false,
          processData: false,
          success: function (data) {
            if ((data.errors)) {
              $this.find(".err-email").html(data.errors.email[0]);
            } else {
              toastr["success"]("You are subscribed successfully!");
              $this.trigger('reset');
              $this.find(".err-email").html('');
            }
          }
        });
      });
    });
  }



  // Mobile Menu
  $('header .main-menu').meanmenu({
    meanMenuContainer: '.mobilemenu',
    meanScreenWidth: '991',
    meanRevealPosition: 'none',
    meanMenuOpen: '<i class="far fa-bars"/>',
    meanMenuClose: '<i class="far fa-times"/>',
    meanMenuCloseSize: '25px'
  });

  // Counter UP InIt
  $('.counter-number').counterUp({
    delay: 100,
    time: 3000
  });

  // Latest Room Slider
  var roomArrow = $('.room-arrows');
  var $status = $('.page-Info');
  var roomSlider = $('#roomSliderActive');
  roomSlider.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
    if (!slick.$dots) {
      return;
    }

    var i = (currentSlide ? currentSlide : 0) + 1;
    var statusText = i > 10 ? i : '0' + i;

    $status.html(
      '<span class="big-num">' +
      statusText +
      '<span class="small">' +
      slick.$dots[0].children.length +
      '</span>' +
      '</span> '
    );
  });

  roomSlider.slick({
    dots: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 3000,
    arrows: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    appendArrows: roomArrow,
    prevArrow:
      '<span class="prev"><i class="fal fa-angle-left"></i></span>',
    nextArrow:
      '<span class="next"><i class="fal fa-angle-right"></i></span>',
    rtl: rtl == 1 ? true : false,
    responsive: [
      {
        breakpoint: 1740,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 700,
        settings: {
          slidesToShow: 1
        }
      }
    ]
  });

  // Feature Slider Active
  $('#featureSlideActive').slick({
    dots: false,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 3000,
    arrows: true,
    speed: 1500,
    slidesToShow: 1,
    slidesToScroll: 1,
    rtl: rtl == 1 ? true : false,
    prevArrow:
      '<span class="prev"><i class="fal fa-angle-double-left"></i></span>',
    nextArrow:
      '<span class="next"><i class="fal fa-angle-double-right"></i></span>'
  });

  // Feedback Slider One
  $('#feedbackSlideActive').slick({
    dots: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 3000,
    arrows: true,
    speed: 1500,
    slidesToShow: 2,
    slidesToScroll: 2,
    rtl: rtl == 1 ? true : false,
    prevArrow:
      '<span class="prev"><i class="fal fa-angle-double-left"></i></span>',
    nextArrow:
      '<span class="next"><i class="fal fa-angle-double-right"></i></span>',
    responsive: [
      {
        breakpoint: 1599,
        settings: {
          arrows: false
        }
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 1,
          arrows: false
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          arrows: false
        }
      }
    ]
  });

  // Feedback Slider Two
  $('#feedSliderTwo').slick({
    dots: true,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 3000,
    arrows: true,
    speed: 1500,
    slidesToShow: 1,
    slidesToScroll: 1,
    rtl: rtl == 1 ? true : false,
    prevArrow:
      '<span class="prev"><i class="fal fa-angle-double-left"></i></span>',
    nextArrow:
      '<span class="next"><i class="fal fa-angle-double-right"></i></span>',
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          arrows: false
        }
      }
    ]
  });

  // Brand Slider Active
  $('#brandsSlideActive').slick({
    dots: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 3000,
    arrows: false,
    speed: 1500,
    slidesToShow: 6,
    slidesToScroll: 1,
    rtl: rtl == 1 ? true : false,
    responsive: [
      {
        breakpoint: 1201,
        settings: {
          slidesToShow: 6
        }
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 4
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: 3
        }
      },
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 2
        }
      }
    ]
  });

  // Bootstrap Accordion Icon
  $('.feature-accordion .card-header button').on('click', function (e) {
    $('.feature-accordion .card-header button').removeClass('active-accordion');
    $(this).addClass('active-accordion');
  });

  // Wow JS And Nice-Select Initialize
  $('select.nice-select').niceSelect();

  new WOW().init();

  $('.video-popup').magnificPopup({
    type: 'iframe'
  });

  // Isotop Active
  $('.gallery-filter li').on('click', function () {
    $('.gallery-filter li').removeClass('active');
    $(this).addClass('active');

    var selector = $(this).attr('data-filter');
    $('.gallery-filter-items').isotope({
      filter: selector
    });
  });

  $(window).on('load', function () {
    $('.gallery-filter-items').isotope();
  });

  // Package Details Slider Image Popup
  $('.gallery-single').magnificPopup({
    type: 'image',
    gallery: {
      enabled: true
    }
  });

  // Show or Hide The 'Back To Top' Button
  $(window).on('scroll', function () {
    if ($(this).scrollTop() > 600) {
      $('.back-to-top').fadeIn(700);
    } else {
      $('.back-to-top').fadeOut(700);
    }
  });

  // Animate The 'Back To Top'
  $('.back-to-top').on('click', function (event) {
    event.preventDefault();

    $('html, body').animate({
      scrollTop: 0
    }, 1500);
  });

  // Room Details Slider
  $('.main-slider').slick({
    dots: false,
    infinite: false,
    autoplay: false,
    autoplaySpeed: 3000,
    arrows: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    asNavFor: '.dots-slider',
    rtl: rtl == 1 ? true : false,
    prevArrow:
      '<span class="prev"><i class="fal fa-angle-double-left"></i></span>',
    nextArrow:
      '<span class="next"><i class="fal fa-angle-double-right"></i></span>'
  });

  $('.dots-slider').slick({
    infinite: false,
    autoplay: false,
    autoplaySpeed: 3000,
    arrows: false,
    slidesToShow: 6,
    slidesToScroll: 1,
    asNavFor: '.main-slider',
    dots: false,
    focusOnSelect: true,
    rtl: rtl == 1 ? true : false,
    responsive: [
      {
        breakpoint: 576,
        settings: {
          slidesToShow: 3
        }
      }
    ]
  });

  // Room Details Slider Image Popup
  $('.main-slider').each(function () {
    // the containers for all your galleries
    var additionalImages = $('.single-img a.main-img').not(
      '.slick-slide.slick-cloned a.main-img'
    );
    additionalImages.magnificPopup({
      type: 'image',
      gallery: {
        enabled: true
      },
      mainClass: 'mfp-fade'
    });
  });

  $('.gallery-items .gallery-item').magnificPopup({
    type: 'image',
    gallery: {
      enabled: true
    }
  });

  // Review Bars
  $('.reviews-bars').bind('inview', function (event, visible, visiblePartX, visiblePartY) {
    $('.bar').each(function () {
      $(this)
        .find('.bar-inner')
        .animate({
          width: $(this).attr('data-width')
        });
    });
  });

  // Slider One
  function sliderOne() {
    var slider = $('#heroSlideActive');

    slider.on('init', function (e, slick) {
      var $firstAnimatingElements = $(
        '.single-hero-slide:first-child'
      ).find('[data-animation]');
      doAnimations($firstAnimatingElements);
    });

    slider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
      var $animatingElements = $('.single-hero-slide[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
      doAnimations($animatingElements);
    });

    slider.slick({
      autoplay: false,
      autoplaySpeed: 10000,
      dots: true,
      fade: true,
      arrows: true,
      infinite: true,
      speed: 1500,
      rtl: rtl == 1 ? true : false,
      prevArrow:
        '<span class="prev"><i class="fal fa-angle-double-left"></i></span>',
      nextArrow:
        '<span class="next"><i class="fal fa-angle-double-right"></i></span>',
      responsive: [
        {
          breakpoint: 768,
          settings: {
            arrows: false
          }
        }
      ]
    });


    function doAnimations(elements) {
      var animationEndEvents =
        'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
      elements.each(function () {
        var $this = $(this);
        var $animationDelay = $this.data('delay');
        var $animationType = 'animated ' + $this.data('animation');

        $this.css({
          'animation-delay': $animationDelay,
          '-webkit-animation-delay': $animationDelay
        });

        $this
          .addClass($animationType)
          .one(animationEndEvents, function () {
            $this.removeClass($animationType);
          });
      });
    }
  }
  sliderOne();

  // Slider Two
  function sliderTwo() {
    var slider = $('#secondSlider');

    slider.on('init', function (e, slick) {
      var $firstAnimatingElements = $(
        '.single-hero-slide:first-child'
      ).find('[data-animation]');
      doAnimations($firstAnimatingElements);
    });

    slider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
      var $animatingElements = $(
        '.single-hero-slide[data-slick-index="' + nextSlide + '"]'
      ).find('[data-animation]');
      doAnimations($animatingElements);
    });

    slider.slick({
      autoplay: false,
      autoplaySpeed: 10000,
      dots: true,
      fade: true,
      arrows: true,
      infinite: true,
      speed: 1500,
      rtl: rtl == 1 ? true : false,
      prevArrow:
        '<span class="prev"><i class="fal fa-angle-double-left"></i></span>',
      nextArrow:
        '<span class="next"><i class="fal fa-angle-double-right"></i></span>',
      responsive: [
        {
          breakpoint: 768,
          settings: {
            arrows: false
          }
        }
      ]
    });

    function doAnimations(elements) {
      var animationEndEvents =
        'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
      elements.each(function () {
        var $this = $(this);
        var $animationDelay = $this.data('delay');
        var $animationType = 'animated ' + $this.data('animation');

        $this.css({
          'animation-delay': $animationDelay,
          '-webkit-animation-delay': $animationDelay
        });

        $this
          .addClass($animationType)
          .one(animationEndEvents, function () {
            $this.removeClass($animationType);
          });
      });
    }
  }
  sliderTwo();

  // background video initialization for home 5
  var options = {
    playOnlyIfVisible: false
  };
  if ($("#bgndVideo").length > 0) {
    $("#bgndVideo").YTPlayer(options);
  }

  // particles effect initialization for home 3
  if ($("#particles-js").length > 0) {
    particlesJS.load('particles-js', 'assets/js/particles.json');
  }

  // ripple effect initialization for home 4
  if ($("#heroHome4").length > 0) {
    $('#heroHome4').ripples({
      resolution: 500,
      dropRadius: 20,
      perturbance: 0.04
    });
  }

  $('.packages-big-slider').slick({
    dots: false,
    arrows: true,
    infinite: false,
    autoplay: false,
    autoplaySpeed: 1500,
    asNavFor: '.packages-thumb-slider',
    slidesToShow: 1,
    slidesToScroll: 1,
    rtl: rtl == 1 ? true : false,
    prevArrow:
      '<span class="prev"><i class="fal fa-angle-double-left"></i></span>',
    nextArrow:
      '<span class="next"><i class="fal fa-angle-double-right"></i></span>'
  });

  $('.packages-thumb-slider').slick({
    dots: false,
    arrows: false,
    infinite: false,
    autoplay: false,
    autoplaySpeed: 1500,
    focusOnSelect: true,
    asNavFor: '.packages-big-slider',
    slidesToShow: 6,
    slidesToScroll: 1,
    rtl: rtl == 1 ? true : false,
    responsive: [
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 3
        }
      }
    ]
  });

  $(".more-ammenities a").on('click', function (e) {
    e.preventDefault();

    $(".checkboxes .show-more").removeClass('d-none');
    $(".checkboxes .show-more").addClass('d-block');

    $(this).hide();
  });

  // lazyload init
  new LazyLoad();
});

// Preloader
$(window).on('load', function (event) {
  if ($(".popup-wrapper").length > 0) {
    let $firstPopup = $(".popup-wrapper").eq(0);
    popupAnnouncement($firstPopup);
  }

  $('#preLoader').fadeOut(500);
});

// scroll to bottom

if ($('.messages').length > 0) {
  $('.messages')[0].scrollTop = $('.messages')[0].scrollHeight;
}

// summernote initialization start
$(".tinymceInit").each(function (i) {

  tinymce.init({
    selector: '.tinymceInit',
    plugins: 'autolink charmap emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    promotion: false,
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ]
  });

});
