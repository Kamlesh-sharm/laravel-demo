<div class="sidebar sidebar-style-2"
  data-background-color="{{ Session::get('vendor_theme_version') == 'light' ? 'white' : 'dark2' }}">
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (Auth::guard('vendor')->user()->photo != null)
            <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('vendor')->user()->photo) }}"
              alt="Vendor Image" class="avatar-img rounded-circle">
          @else
            <img src="{{ asset('assets/img/blank_user.jpg') }}" alt="" class="avatar-img rounded-circle">
          @endif
        </div>

        <div class="info">
          <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
            <span>
              {{ Auth::guard('vendor')->user()->username }}
              <span class="user-level">{{ __('Vendor') }}</span>
              <span class="caret"></span>
            </span>
          </a>

          <div class="clearfix"></div>

          <div class="collapse in" id="adminProfileMenu">
            <ul class="nav">
              <li>
                <a href="{{ route('vendor.edit.profile') }}">
                  <span class="link-collapse">{{ __('Edit Profile') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('vendor.change_password') }}">
                  <span class="link-collapse">{{ __('Change Password') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('vendor.logout') }}">
                  <span class="link-collapse">{{ __('Logout') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>


      <ul class="nav nav-primary">
        {{-- search --}}
        <div class="row mb-3">
          <div class="col-12">
            <form>
              <div class="form-group py-0">
                <input name="term" type="text" class="form-control sidebar-search ltr"
                  placeholder="Search Menu Here...">
              </div>
            </form>
          </div>
        </div>

        {{-- dashboard --}}
        <li class="nav-item @if (request()->routeIs('vendor.dashboard')) active @endif">
          <a href="{{ route('vendor.dashboard') }}">
            <i class="fal fa-tachometer-alt-average"></i>
            <p>{{ __('Dashboard') }}</p>
          </a>
        </li>

        <li
          class="nav-item @if (request()->routeIs('vendor.rooms_management.rooms')) active
            @elseif (request()->routeIs('vendor.rooms_management.create_room')) active
            @elseif (request()->routeIs('vendor.rooms_management.edit_room')) active @endif">
          <a data-toggle="collapse" href="#rooms">
            <i class="fal fa-home"></i>
            <p class="pr-2">{{ __('Rooms Management') }}</p>
            <span class="caret"></span>
          </a>
          <div id="rooms"
            class="collapse
              @if (request()->routeIs('vendor.rooms_management.rooms')) show
              @elseif (request()->routeIs('vendor.rooms_management.create_room')) show
              @elseif (request()->routeIs('vendor.rooms_management.edit_room')) show @endif">
            <ul class="nav nav-collapse">

              <li
                class="@if (request()->routeIs('vendor.rooms_management.rooms')) active
                  @elseif (request()->routeIs('vendor.rooms_management.edit_room')) active @endif">
                <a href="{{ route('vendor.rooms_management.rooms') }}">
                  <span class="sub-item">{{ __('Manage Rooms') }}</span>
                </a>
              </li>

              <li class="@if (request()->routeIs('vendor.rooms_management.create_room')) active @endif">
                <a href="{{ route('vendor.rooms_management.create_room') }}">
                  <span class="sub-item">{{ __('Add Room') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li
          class="nav-item @if (request()->routeIs('vendor.room_bookings.all_bookings')) active
            @elseif (request()->routeIs('vendor.room_bookings.paid_bookings')) active
            @elseif (request()->routeIs('vendor.room_bookings.unpaid_bookings')) active
            @elseif (request()->routeIs('vendor.room_bookings.booking_details_and_edit')) active
            @elseif (request()->routeIs('vendor.room_bookings.booking_form')) active @endif">
          <a data-toggle="collapse" href="#roomBookings">
            <i class="far fa-calendar-check"></i>
            <p class="pr-2">{{ __('Room Bookings') }}</p>
            <span class="caret"></span>
          </a>
          <div id="roomBookings"
            class="collapse
              @if (request()->routeIs('vendor.room_bookings.all_bookings')) show
              @elseif (request()->routeIs('vendor.room_bookings.paid_bookings')) show
              @elseif (request()->routeIs('vendor.room_bookings.unpaid_bookings')) show
              @elseif (request()->routeIs('vendor.room_bookings.booking_details_and_edit')) show
              @elseif (request()->routeIs('vendor.room_bookings.booking_form')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('vendor.room_bookings.all_bookings') ? 'active' : '' }}">
                <a href="{{ route('vendor.room_bookings.all_bookings') }}">
                  <span class="sub-item">{{ __('All Bookings') }}</span>
                </a>
              </li>
              <li class="{{ request()->routeIs('vendor.room_bookings.paid_bookings') ? 'active' : '' }}">
                <a href="{{ route('vendor.room_bookings.paid_bookings') }}">
                  <span class="sub-item">{{ __('Paid Bookings') }}</span>
                </a>
              </li>
              <li class="{{ request()->routeIs('vendor.room_bookings.unpaid_bookings') ? 'active' : '' }}">
                <a href="{{ route('vendor.room_bookings.unpaid_bookings') }}">
                  <span class="sub-item">{{ __('Unpaid Bookings') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li
          class="nav-item @if (request()->routeIs('vendor.packages_management.packages')) active
            @elseif (request()->routeIs('vendor.packages_management.create_package')) active
            @elseif (request()->routeIs('vendor.packages_management.edit_package')) active
            @elseif (request()->routeIs('vendor.packages_management.view_locations')) active
            @elseif (request()->routeIs('vendor.packages_management.view_plans')) active @endif">
          <a data-toggle="collapse" href="#packages">
            <i class="fal fa-box-alt"></i>
            <p>{{ __('Packages Management') }}</p>
            <span class="caret"></span>
          </a>
          <div id="packages"
            class="collapse
              @if (request()->routeIs('vendor.packages_management.packages')) show
              @elseif (request()->routeIs('vendor.packages_management.create_package')) show
              @elseif (request()->routeIs('vendor.packages_management.edit_package')) show
              @elseif (request()->routeIs('vendor.packages_management.view_locations')) show
              @elseif (request()->routeIs('vendor.packages_management.view_plans')) show @endif">
            <ul class="nav nav-collapse">

              <li class="@if (request()->routeIs('vendor.packages_management.create_package')) active @endif">
                <a href="{{ route('vendor.packages_management.create_package') }}">
                  <span class="sub-item">{{ __('Add Package') }}</span>
                </a>
              </li>

              <li
                class="@if (request()->routeIs('vendor.packages_management.packages')) active
                  @elseif (request()->routeIs('vendor.packages_management.edit_package')) active
                  @elseif (request()->routeIs('vendor.packages_management.view_locations')) active
                  @elseif (request()->routeIs('vendor.packages_management.view_plans')) active @endif">
                <a href="{{ route('vendor.packages_management.packages') }}">
                  <span class="sub-item">{{ __('Packages') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li
          class="nav-item @if (request()->routeIs('vendor.package_bookings.all_bookings')) active
            @elseif (request()->routeIs('vendor.package_bookings.paid_bookings')) active
            @elseif (request()->routeIs('vendor.package_bookings.unpaid_bookings')) active 
            @elseif (request()->routeIs('vendor.package_bookings.booking_details')) active @endif">
          <a data-toggle="collapse" href="#packageBookings">
            <i class="far fa-calendar-check"></i>
            <p>{{ __('Package Bookings') }}</p>
            <span class="caret"></span>
          </a>
          <div id="packageBookings"
            class="collapse
              @if (request()->routeIs('vendor.package_bookings.all_bookings')) show
              @elseif (request()->routeIs('vendor.package_bookings.paid_bookings')) show
              @elseif (request()->routeIs('vendor.package_bookings.unpaid_bookings')) show 
              @elseif (request()->routeIs('vendor.package_bookings.booking_details')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('vendor.package_bookings.all_bookings') ? 'active' : '' }}">
                <a href="{{ route('vendor.package_bookings.all_bookings') }}">
                  <span class="sub-item">{{ __('All Bookings') }}</span>
                </a>
              </li>
              <li class="{{ request()->routeIs('vendor.package_bookings.paid_bookings') ? 'active' : '' }}">
                <a href="{{ route('vendor.package_bookings.paid_bookings') }}">
                  <span class="sub-item">{{ __('Paid Bookings') }}</span>
                </a>
              </li>
              <li class="{{ request()->routeIs('vendor.package_bookings.unpaid_bookings') ? 'active' : '' }}">
                <a href="{{ route('vendor.package_bookings.unpaid_bookings') }}">
                  <span class="sub-item">{{ __('Unpaid Bookings') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li
          class="nav-item @if (request()->routeIs('vendor.withdraw')) active 
            @elseif (request()->routeIs('vendor.withdraw.create'))  active @endif">
          <a data-toggle="collapse" href="#Withdrawals">
            <i class="fal fa-donate"></i>
            <p>{{ __('Withdrawals') }}</p>
            <span class="caret"></span>
          </a>

          <div id="Withdrawals"
            class="collapse 
              @if (request()->routeIs('vendor.withdraw')) show 
              @elseif (request()->routeIs('vendor.withdraw.create')) show @endif">
            <ul class="nav nav-collapse">
              <li class="{{ request()->routeIs('vendor.withdraw') ? 'active' : '' }}">
                <a href="{{ route('vendor.withdraw', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Withdrawal Requests') }}</span>
                </a>
              </li>

              <li class="{{ request()->routeIs('vendor.withdraw.create') ? 'active' : '' }}">
                <a href="{{ route('vendor.withdraw.create', ['language' => $defaultLang->code]) }}">
                  <span class="sub-item">{{ __('Make a Request') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </li>




        <li class="nav-item @if (request()->routeIs('vendor.transcation')) active @endif">
          <a href="{{ route('vendor.transcation') }}">
            <i class="fal fa-exchange-alt"></i>
            <p>{{ __('Transactions') }}</p>
          </a>
        </li>
        @php
          $support_status = DB::table('support_ticket_statuses')->first();
        @endphp
        @if ($support_status->support_ticket_status == 'active')
          {{-- Support Ticket --}}
          <li
            class="nav-item @if (request()->routeIs('vendor.support_tickets')) active
            @elseif (request()->routeIs('vendor.support_tickets.message')) active
            @elseif (request()->routeIs('vendor.support_ticket.create')) active @endif">
            <a data-toggle="collapse" href="#support_ticket">
              <i class="la flaticon-web-1"></i>
              <p>{{ __('Support Tickets') }}</p>
              <span class="caret"></span>
            </a>

            <div id="support_ticket"
              class="collapse
              @if (request()->routeIs('vendor.support_tickets')) show
              @elseif (request()->routeIs('vendor.support_tickets.message')) show
              @elseif (request()->routeIs('vendor.support_ticket.create')) show @endif">
              <ul class="nav nav-collapse">

                <li
                  class="{{ request()->routeIs('vendor.support_tickets') && empty(request()->input('status')) ? 'active' : '' }}">
                  <a href="{{ route('vendor.support_tickets') }}">
                    <span class="sub-item">{{ __('All Tickets') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('vendor.support_ticket.create') ? 'active' : '' }}">
                  <a href="{{ route('vendor.support_ticket.create') }}">
                    <span class="sub-item">{{ __('Add a Ticket') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif


        <li class="nav-item @if (request()->routeIs('vendor.edit.profile')) active @endif">
          <a href="{{ route('vendor.edit.profile') }}">
            <i class="fal fa-user-edit"></i>
            <p>{{ __('Edit Profile') }}</p>
          </a>
        </li>
        <li class="nav-item @if (request()->routeIs('vendor.change_password')) active @endif">
          <a href="{{ route('vendor.change_password') }}">
            <i class="fal fa-key"></i>
            <p>{{ __('Change Password') }}</p>
          </a>
        </li>

        <li class="nav-item @if (request()->routeIs('vendor.logout')) active @endif">
          <a href="{{ route('vendor.logout') }}">
            <i class="fal fa-sign-out"></i>
            <p>{{ __('Logout') }}</p>
          </a>
        </li>

      </ul>
    </div>
  </div>
</div>
