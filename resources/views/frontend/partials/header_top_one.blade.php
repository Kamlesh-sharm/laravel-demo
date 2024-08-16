<div class="header-top-area section-bg">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-xxl-4 col-xl-4 col-lg-7 col-md-6 d-md-block d-none">
        <ul class="top-contact-info list-inline">
          @if (!is_null($websiteInfo->address))
            <li><i class="far fa-map-marker-alt"></i>{{ $websiteInfo->address }}</li>
          @endif

          @if (!is_null($websiteInfo->support_contact))
            <li>
                <i class="far fa-phone"></i>
                <a href="tel:{{ $websiteInfo->support_contact }}">{{ $websiteInfo->support_contact }}</a>
            </li>
          @endif
        </ul>
      </div>
      <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-6">
        <div class="top-right">
          <ul class="top-menu list-inline d-inline">
            <li>
              <div class="dropdown">
                <button type="button"
                  class="btn btn-primary menu-btn dropdown-toggle {{ $currentLanguageInfo->direction == 0 ? 'mr-1' : 'ml-1' }}"
                  data-toggle="dropdown">{{ __('Customer') }}</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  @guest('web')
                    <a class="dropdown-item" href="{{ route('user.login') }}">{{ __('Login') }}</a>
                    <a class="dropdown-item" href="{{ route('user.signup') }}">{{ __('Signup') }}</a>
                  @endguest

                  @auth('web')
                    <a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                    <a class="dropdown-item" href="{{ route('user.logout') }}">{{ __('Logout') }}</a>
                  @endauth
                </div>
              </div>
            </li>
            <li>
              <div class="dropdown">
                <button type="button"
                  class="btn btn-outline-primary menu-btn dropdown-toggle {{ $currentLanguageInfo->direction == 0 ? 'mr-1' : 'ml-1' }}"
                  data-toggle="dropdown">{{ __('Vendor') }}</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  @guest('vendor')
                    <a class="dropdown-item" href="{{ route('vendor.login') }}">{{ __('Login') }}</a>
                    <a class="dropdown-item" href="{{ route('vendor.signup') }}">{{ __('Signup') }}</a>
                  @endguest

                  @auth('vendor')
                    <a class="dropdown-item" href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }}</a>
                    <a class="dropdown-item" href="{{ route('vendor.logout') }}">{{ __('Logout') }}</a>
                  @endauth
                </div>
              </div>
            </li>
          </ul>

          @if (count($socialLinkInfos) > 0)
            <ul class="top-social-icon list-inline d-md-inline-block d-none">
              @foreach ($socialLinkInfos as $socialLinkInfo)
                <li>
                  <a href="{{ $socialLinkInfo->url }}"><i class="{{ $socialLinkInfo->icon }}"></i></a>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
