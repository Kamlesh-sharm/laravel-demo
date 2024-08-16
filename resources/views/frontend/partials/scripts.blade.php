<script>
  "use strict";

  var rtl = {{ $currentLanguageInfo->direction }};
  var baseURL = "{!! url('/') !!}";
  var vapid_public_key = "{!! env('VAPID_PUBLIC_KEY') !!}";
</script>

{{-- modernizr js --}}
<script src="{{ asset('assets/js/modernizr-3.6.0.min.js') }}"></script>

{{-- My js --}}
<script src="{{ asset('assets/js/meanmenu.js.download') }}"></script>
<script src="{{ asset('assets/js/swiper-bundle.js.download') }}"></script>
<script src="{{ asset('assets/js/slick.js.download') }}"></script>
<script src="{{ asset('assets/js/magnific-popup.js.download') }}"></script>
<script src="{{ asset('assets/js/backtotop.js.download') }}"></script>
<script src="{{ asset('assets/js/nice-select.js.download') }}"></script>
<script src="{{ asset('assets/js/sidebar.js.download') }}"></script>
<script src="{{ asset('assets/js/isotope-pkgd.js.download') }}"></script>
<script src="{{ asset('assets/js/imagesloaded-pkgd.js.download') }}"></script>
<script src="{{ asset('assets/js/ajax-form.js.download') }}"></script>
<script src="{{ asset('assets/js/settings.js.download') }}"></script>
<script src="{{ asset('assets/js/wow.min.js.download') }}"></script>
<script src="{{ asset('assets/js/main.js.download') }}"></script>

{{-- jQuery --}}
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

{{-- popper js --}}
<script src="{{ asset('assets/js/popper.min.js') }}"></script>

{{-- bootstrap js --}}
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

{{-- jQuery-ui js --}}
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>

{{-- Plugins js --}}
<script src="{{ asset('assets/js/plugins.min.js') }}"></script>

@if (session()->has('success'))
  <script>
    "use strict";
    toastr['success']("{{ __(session('success')) }}");
  </script>
@endif

@if (session()->has('error'))
  <script>
    "use strict";
    toastr['error']("{{ __(session('error')) }}");
  </script>
@endif
<script src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

{{-- main js --}}
<script src="{{ asset('assets/js/main.js') }}"></script>

{{-- push-notification js --}}
<script src="{{ asset('assets/js/push-notification.js') }}"></script>
<script src="{{ asset('assets/js/vendor.js') }}"></script>

{{-- whatsapp init code --}}
@if ($websiteInfo->is_whatsapp == 1)
  <script type="text/javascript">
    var whatsapp_popup = {{ $websiteInfo->whatsapp_popup }};
    var whatsappImg = "{{ asset('assets/img/whatsapp.svg') }}";

    $(function() {
      $('#WAButton').floatingWhatsApp({
        phone: "{{ $websiteInfo->whatsapp_number }}", //WhatsApp Business phone number
        headerTitle: "{{ $websiteInfo->whatsapp_header_title }}", //Popup Title
        popupMessage: `{!! nl2br($websiteInfo->whatsapp_popup_message) !!}`, //Popup Message
        showPopup: whatsapp_popup == 1 ? true : false, //Enables popup display
        buttonImage: '<img src="' + whatsappImg + '" />', //Button Image
        position: "right" //Position: left | right
      });
    });
  </script>
@endif

<!--Start of Tawk.to Script-->
@if ($websiteInfo->is_tawkto == 1)
  <script type="text/javascript">
    var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();

    (function() {
      var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/{{ $websiteInfo->tawkto_property_id }}/default';
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  </script>
@endif
<!--End of Tawk.to Script-->
