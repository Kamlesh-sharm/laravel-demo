<!DOCTYPE html>
<html>
  <head>
    {{-- required meta tags --}}
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta
      name='viewport'
      content='width=device-width, initial-scale=1.0, shrink-to-fit=no'
    >

    {{-- csrf-token for ajax request --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- title --}}
    <title>{{ 'Admin | ' . $websiteInfo->website_title }}</title>

    {{-- fav icon --}}
    <link
      rel="shortcut icon"
      type="image/png"
      href="{{ asset('assets/img/' . $websiteInfo->favicon) }}"
    >

    {{-- include styles --}}
    @includeIf('backend.partials.styles')

    {{-- some additional style --}}
    @yield('style')
  </head>

  <body @if(request()->cookie('admin-theme') == 'dark') data-background-color="dark" @endif>
    {{-- loader start --}}
    <div class="request-loader">
      <img src="{{asset('assets/img/loader.gif')}}" alt="loader">
    </div>
    {{-- loader end --}}

    <div class="wrapper
    @if(request()->routeIs('admin.file-manager'))
    overlay-sidebar
    @endif">
      {{-- top navbar area start --}}
      @includeIf('backend.partials.top_navbar')
      {{-- top navbar area end --}}

      {{-- side navbar area start --}}
      @includeIf('backend.partials.side_navbar')
      {{-- side navbar area end --}}

      <div class="main-panel">
        <div class="content">
          <div class="page-inner">
            @yield('content')
          </div>
        </div>

        {{-- footer area start --}}
        @includeIf('backend.partials.footer')
        {{-- footer area end --}}
      </div>
    </div>

    {{-- include scripts --}}
    @includeIf('backend.partials.scripts')

    {{-- some additional script --}}
    @yield('script')
  </body>
</html>
