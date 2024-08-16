<!DOCTYPE html>
<html @if ($currentLanguageInfo->direction == 1) dir="rtl" @endif>

<head>
  {{-- required meta tags --}}
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <meta name="description" content="@yield('meta-description')">
  <meta name="keywords" content="@yield('meta-keywords')">

  {{-- csrf-token for ajax request --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- title --}}
  <title>@yield('pageHeading') | {{ $websiteInfo->website_title }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

  {{-- include styles --}}
  @includeIf('frontend.partials.styles')
</head>

<body>
  {{-- preloader start --}}
  @if ($websiteInfo->preloader_status == 1)
    <div class="loader" id="preLoader">
      <img class="lazy" data-src="{{ asset('assets/img/' . $websiteInfo->preloader) }}" alt="">
    </div>
  @endif
  {{-- preloader end --}}

  {{-- header start --}}
  <header class="@if ($websiteInfo->theme_version == 'theme_two') home-two @endif">

    {{-- include header-nav --}}
    @if ($websiteInfo->theme_version == 'theme_one')
      {{-- include header-top --}}
      @includeIf('frontend.partials.header_top_one')
      @includeIf('frontend.partials.header_nav_one')
    @elseif ($websiteInfo->theme_version == 'theme_two')
      {{-- include header-top --}}
      @includeIf('frontend.partials.header_top_two')
      @includeIf('frontend.partials.header_nav_two')
    @endif
  </header>
  {{-- header end --}}

  @yield('content')

  {{-- back to top start --}}
  <div class="back-top">
    <a href="#" class="back-to-top">
      <i class="far fa-angle-up"></i>
    </a>
  </div>
  {{-- back to top end --}}


  {{-- include footer --}}
  @includeIf('frontend.partials.footer')

  {{-- Popups start --}}
  @includeIf('frontend.partials.popups')
  {{-- Popups end --}}

  {{-- WhatsApp Chat Button --}}
  <div id="WAButton"></div>

  {{-- Cookie alert dialog start --}}
  @if (!empty($cookie) && $cookie->cookie_alert_status == 1)
    <div class="cookie">
      @include('cookie-consent::index')
    </div>
  @endif
  {{-- Cookie alert dialog end --}}

  {{-- include scripts --}}
  @includeIf('frontend.partials.scripts')

  {{-- additional script --}}
  @yield('script')
  <!-- Messenger Chat Plugin Code -->

</body>

</html>
