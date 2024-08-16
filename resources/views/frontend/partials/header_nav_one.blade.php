<div class="header-menu-area">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-lg-2 col-md-4 col-7">
        @if (!is_null($websiteInfo->logo))
          <div class="logo">
            <a href="{{ route('index') }}">
              <img class="lazy" data-src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="website logo">
            </a>
          </div>
        @endif
      </div>

      <div class="col-lg-10 col-md-8 col-5">
        <div class="menu-right-area text-right">
          <div class="lang-select">
            <div class="lang-img">
              <img class="lazy" data-src="{{ asset('assets/img/icons/languages.png') }}" alt="flag"
                width="45">
            </div>

            <div class="lang-option">
              <form action="{{ route('change_language') }}" method="GET">
                <select class="nice-select" name="lang_code" onchange="this.form.submit()">
                  @foreach ($allLanguageInfos as $languageInfo)
                    <option value="{{ $languageInfo->code }}"
                      {{ $languageInfo->code == $currentLanguageInfo->code ? 'selected' : '' }}>
                      {{ $languageInfo->name }}
                    </option>
                  @endforeach
                </select>
              </form>
            </div>
          </div>

          @php
            $links = json_decode($menus, true);
          @endphp
          <nav class="main-menu">
            <ul class="list-inline">
              @foreach ($links as $link)
                @php
                  $href = getHref($link, $currentLanguageInfo->id);
                @endphp

                @if (!array_key_exists('children', $link))
                  {{-- - Level1 links which doesn't have dropdown menus - --}}
                  <li><a href="{{ $href }}" target="{{ $link['target'] }}">{{ $link['text'] }}</a></li>
                @else
                  <li class="have-submenu">
                    {{-- - Level1 links which has dropdown menus - --}}
                    <a href="{{ $href }}" target="{{ $link['target'] }}">{{ $link['text'] }}</a>

                    <ul class="submenu">
                      {{-- START: 2nd level links --}}
                      @foreach ($link['children'] as $level2)
                        @php
                          $l2Href = getHref($level2, $currentLanguageInfo->id);
                        @endphp

                        <li @if (array_key_exists('children', $level2)) class="have-submenu" @endif>
                          <a href="{{ $l2Href }}" target="{{ $level2['target'] }}">{{ $level2['text'] }}</a>

                          @if (array_key_exists('children', $level2))
                            <ul class="submenu">
                              @foreach ($level2['children'] as $level3)
                                @php
                                  $l3Href = getHref($level3, $currentLanguageInfo->id);
                                @endphp
                                <li><a href="{{ $l3Href }}"
                                    target="{{ $level3['target'] }}">{{ $level3['text'] }}</a></li>
                              @endforeach
                            </ul>
                          @endif
                        </li>
                      @endforeach
                      {{-- END: 2nd level links --}}
                    </ul>

                  </li>
                @endif
              @endforeach
            </ul>
          </nav>

        </div>
      </div>
    </div>

    <div class="mobilemenu"></div>
  </div>
</div>
