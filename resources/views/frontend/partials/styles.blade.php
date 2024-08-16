{{-- bootstrap css --}}
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

{{-- jQuery-ui css --}}
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">

{{-- plugins css --}}
<link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">

{{-- default css --}}
<link rel="stylesheet" href="{{ asset('assets/css/default.css') }}">

{{-- main css --}}
<link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendor.css') }}">

{{-- My css --}}
<link rel="stylesheet" href="{{ asset('assets/css/meanmenu.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/nouislider.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/backtotop.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/flaticon_royel.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome-pro.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/spacing.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/mymain.css') }}">

{{-- responsive css --}}
<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

{{-- right-to-left css --}}
@if ($currentLanguageInfo->direction == 1)
  <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/vendor_rtl.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/rtl-responsive.css') }}">
@endif

{{-- base-color css using a php file --}}
<link rel="stylesheet"
  href="{{ asset('assets/css/base-color.php?color1=' . $websiteInfo->primary_color . '&color2=' . $websiteInfo->secondary_color) }}">

<style>
  .breadcrumb-area::after {
    background-color: #{{ $websiteInfo->breadcrumb_overlay_color }};
    opacity: {{ $websiteInfo->breadcrumb_overlay_opacity }};
  }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/tinymce-content.css') }}">
@yield('custom-style')
