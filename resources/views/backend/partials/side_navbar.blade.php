<div class="sidebar sidebar-style-2" @if (request()->cookie('admin-theme') == 'dark') data-background-color="dark2" @endif>
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (Auth::guard('admin')->user()->image != null)
            <img src="{{ asset('assets/img/admins/' . Auth::guard('admin')->user()->image) }}" alt="Admin Image"
              class="avatar-img rounded-circle">
          @else
            <img src="{{ asset('assets/img/blank_user.jpg') }}" alt="Admin Image" class="avatar-img rounded-circle">
          @endif
        </div>
        <div class="info">
          <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
            <span>
              {{ Auth::guard('admin')->user()->first_name }}
              @if (Auth::guard('admin')->user()->role_id == null)
                <span class="user-level">{{ __('Super Admin') }}</span>
              @else
                <span class="user-level">{{ @Auth::guard('admin')->user()->role->name }}</span>
              @endif
              <span class="caret"></span>
            </span>
          </a>
          <div class="clearfix"></div>
          <div class="collapse in" id="adminProfileMenu">
            <ul class="nav">
              <li>
                <a href="{{ route('admin.edit_profile') }}">
                  <span class="link-collapse">{{ __('Edit Profile') }}</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.change_password') }}">
                  <span class="link-collapse">{{ __('Change Password') }}</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.logout') }}">
                  <span class="link-collapse">{{ __('Logout') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <ul class="nav nav-primary mt-0">
        <div class="row mb-2">
          <div class="col-12">
            <form action="">
              <div class="form-group py-0">
                <input name="term" type="text" class="form-control sidebar-search ltr"
                  placeholder="Search Menu Here...">
              </div>
            </form>
          </div>
        </div>

        {{-- dashboard --}}
        <li class="nav-item @if (request()->routeIs('admin.dashboard')) active @endif">
          <a href="{{ route('admin.dashboard') }}">
            <i class="la flaticon-paint-palette"></i>
            <p>Dashboard</p>
          </a>
        </li>

        @if (empty($admin->role) || (!empty($permissions) && in_array('Rooms Management', $permissions)))
          {{-- rooms management --}}
          <li
            class="nav-item @if (request()->routeIs('admin.rooms_management.settings')) active
            @elseif (request()->routeIs('admin.rooms_management.coupons')) active
            @elseif (request()->routeIs('admin.rooms_management.amenities')) active
            @elseif (request()->routeIs('admin.rooms_management.categories')) active
            @elseif (request()->routeIs('admin.rooms_management.rooms')) active
            @elseif (request()->routeIs('admin.rooms_management.create_room')) active
            @elseif (request()->routeIs('admin.rooms_management.edit_room')) active @endif">
            <a data-toggle="collapse" href="#rooms">
              <i class="fal fa-home"></i>
              <p class="pr-2">{{ __('Rooms Management') }}</p>
              <span class="caret"></span>
            </a>
            <div id="rooms"
              class="collapse
              @if (request()->routeIs('admin.rooms_management.settings')) show
              @elseif (request()->routeIs('admin.rooms_management.coupons')) show
              @elseif (request()->routeIs('admin.rooms_management.amenities')) show
              @elseif (request()->routeIs('admin.rooms_management.categories')) show
              @elseif (request()->routeIs('admin.rooms_management.rooms')) show
              @elseif (request()->routeIs('admin.rooms_management.create_room')) show
              @elseif (request()->routeIs('admin.rooms_management.edit_room')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.rooms_management.settings') ? 'active' : '' }}">
                  <a href="{{ route('admin.rooms_management.settings') }}">
                    <span class="sub-item">{{ __('Settings') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.rooms_management.coupons') ? 'active' : '' }}">
                  <a href="{{ route('admin.rooms_management.coupons') }}">
                    <span class="sub-item">{{ __('Coupons') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.rooms_management.amenities') ? 'active' : '' }}">
                  <a href="{{ route('admin.rooms_management.amenities') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">Amenities</span>
                  </a>
                </li>
                @if ($websiteInfo->room_category_status == 1)
                  <li class="{{ request()->routeIs('admin.rooms_management.categories') ? 'active' : '' }}">
                    <a href="{{ route('admin.rooms_management.categories') . '?language=' . $defaultLang->code }}">
                      <span class="sub-item">Categories</span>
                    </a>
                  </li>
                @endif
                <li
                  class="@if (request()->routeIs('admin.rooms_management.rooms')) active
                  @elseif (request()->routeIs('admin.rooms_management.create_room')) active
                  @elseif (request()->routeIs('admin.rooms_management.edit_room')) active @endif">
                  <a href="{{ route('admin.rooms_management.rooms') }}">
                    <span class="sub-item">Rooms</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Room Bookings', $permissions)))
          {{-- Room Bookings --}}
          <li
            class="nav-item @if (request()->routeIs('admin.room_bookings.all_bookings')) active
            @elseif (request()->routeIs('admin.room_bookings.paid_bookings')) active
            @elseif (request()->routeIs('admin.room_bookings.unpaid_bookings')) active
            @elseif (request()->routeIs('admin.room_bookings.booking_details_and_edit')) active
            @elseif (request()->routeIs('admin.room_bookings.booking_details')) active
            @elseif (request()->routeIs('admin.room_bookings.booking_form')) active @endif">
            <a data-toggle="collapse" href="#roomBookings">
              <i class="far fa-calendar-check"></i>
              <p class="pr-2">Room Bookings</p>
              <span class="caret"></span>
            </a>
            <div id="roomBookings"
              class="collapse
              @if (request()->routeIs('admin.room_bookings.all_bookings')) show
              @elseif (request()->routeIs('admin.room_bookings.paid_bookings')) show
              @elseif (request()->routeIs('admin.room_bookings.unpaid_bookings')) show
              @elseif (request()->routeIs('admin.room_bookings.booking_details')) show
              @elseif (request()->routeIs('admin.room_bookings.booking_details_and_edit')) show
              @elseif (request()->routeIs('admin.room_bookings.booking_form')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.room_bookings.all_bookings') ? 'active' : '' }}">
                  <a href="{{ route('admin.room_bookings.all_bookings') }}">
                    <span class="sub-item">{{ __('All Bookings') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.room_bookings.paid_bookings') ? 'active' : '' }}">
                  <a href="{{ route('admin.room_bookings.paid_bookings') }}">
                    <span class="sub-item">{{ __('Paid Bookings') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.room_bookings.unpaid_bookings') ? 'active' : '' }}">
                  <a href="{{ route('admin.room_bookings.unpaid_bookings') }}">
                    <span class="sub-item">{{ __('Unpaid Bookings') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Packages Management', $permissions)))
          {{-- Packages Management --}}
          <li
            class="nav-item @if (request()->routeIs('admin.packages_management.settings')) active
            @elseif (request()->routeIs('admin.packages_management.coupons')) active
            @elseif (request()->routeIs('admin.packages_management.categories')) active
            @elseif (request()->routeIs('admin.packages_management.packages')) active
            @elseif (request()->routeIs('admin.packages_management.create_package')) active
            @elseif (request()->routeIs('admin.packages_management.edit_package')) active
            @elseif (request()->routeIs('admin.packages_management.view_locations')) active
            @elseif (request()->routeIs('admin.packages_management.view_plans')) active @endif">
            <a data-toggle="collapse" href="#packages">
              <i class="fal fa-box-alt"></i>
              <p>{{ __('Packages Management') }}</p>
              <span class="caret"></span>
            </a>
            <div id="packages"
              class="collapse
              @if (request()->routeIs('admin.packages_management.settings')) show
              @elseif (request()->routeIs('admin.packages_management.coupons')) show
              @elseif (request()->routeIs('admin.packages_management.categories')) show
              @elseif (request()->routeIs('admin.packages_management.packages')) show
              @elseif (request()->routeIs('admin.packages_management.create_package')) show
              @elseif (request()->routeIs('admin.packages_management.edit_package')) show
              @elseif (request()->routeIs('admin.packages_management.view_locations')) show
              @elseif (request()->routeIs('admin.packages_management.view_plans')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.packages_management.settings') ? 'active' : '' }}">
                  <a href="{{ route('admin.packages_management.settings') }}">
                    <span class="sub-item">{{ __('Settings') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.packages_management.coupons') ? 'active' : '' }}">
                  <a href="{{ route('admin.packages_management.coupons') }}">
                    <span class="sub-item">{{ __('Coupons') }}</span>
                  </a>
                </li>
                <li
                  class="{{ request()->routeIs('admin.packages_management.categories') ? 'active' : '' }} {{ $settings->package_category_status == 1 ? '' : 'd-none' }}">
                  <a href="{{ route('admin.packages_management.categories') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">Categories</span>
                  </a>
                </li>
                <li
                  class="@if (request()->routeIs('admin.packages_management.packages')) active
                  @elseif (request()->routeIs('admin.packages_management.create_package')) active
                  @elseif (request()->routeIs('admin.packages_management.edit_package')) active
                  @elseif (request()->routeIs('admin.packages_management.view_locations')) active
                  @elseif (request()->routeIs('admin.packages_management.view_plans')) active @endif">
                  <a href="{{ route('admin.packages_management.packages') }}">
                    <span class="sub-item">Packages</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Package Bookings', $permissions)))
          {{-- Package Bookings --}}
          <li
            class="nav-item @if (request()->routeIs('admin.package_bookings.all_bookings')) active
            @elseif (request()->routeIs('admin.package_bookings.paid_bookings')) active
            @elseif (request()->routeIs('admin.package_bookings.unpaid_bookings')) active 
            @elseif (request()->routeIs('admin.package_bookings.booking_details')) active @endif">
            <a data-toggle="collapse" href="#packageBookings">
              <i class="far fa-calendar-check"></i>
              <p>Package Bookings</p>
              <span class="caret"></span>
            </a>
            <div id="packageBookings"
              class="collapse
              @if (request()->routeIs('admin.package_bookings.all_bookings')) show
              @elseif (request()->routeIs('admin.package_bookings.paid_bookings')) show
              @elseif (request()->routeIs('admin.package_bookings.unpaid_bookings')) show 
              @elseif (request()->routeIs('admin.package_bookings.booking_details')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.package_bookings.all_bookings') ? 'active' : '' }}">
                  <a href="{{ route('admin.package_bookings.all_bookings') }}">
                    <span class="sub-item">{{ __('All Bookings') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.package_bookings.paid_bookings') ? 'active' : '' }}">
                  <a href="{{ route('admin.package_bookings.paid_bookings') }}">
                    <span class="sub-item">{{ __('Paid Bookings') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.package_bookings.unpaid_bookings') ? 'active' : '' }}">
                  <a href="{{ route('admin.package_bookings.unpaid_bookings') }}">
                    <span class="sub-item">{{ __('Unpaid Bookings') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Menu Builder', $permissions)))
          {{-- Menu Builder --}}
          <li class="nav-item @if (request()->path() == 'admin/menu-builder') active @endif">
            <a href="{{ route('admin.menu_builder.index') . '?language=' . $defaultLang->code }}">
              <i class="fas fa-bars"></i>
              <p>{{ __('Menu Builder') }}</p>
            </a>
          </li>
        @endif

        {{-- withdraw method --}}
        @if (empty($admin->role) || (!empty($permissions) && in_array('Withdraw Method', $permissions)))
          <li
            class="nav-item
          @if (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw.payment_method')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) active
          @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) active
          @elseif (request()->routeIs('admin.withdraw.withdraw_request')) active @endif">
            <a data-toggle="collapse" href="#withdraw_method">
              <i class="fal fa-credit-card"></i>
              <p>{{ __('Withdrawals Management') }}</p>
              <span class="caret"></span>
            </a>

            <div id="withdraw_method"
              class="collapse
            @if (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw.payment_method')) show
            @elseif (request()->routeIs('admin.withdraw_payment_method.mange_input')) show
            @elseif (request()->routeIs('admin.withdraw_payment_method.edit_input')) show
            @elseif (request()->routeIs('admin.withdraw.withdraw_request')) show @endif">
              <ul class="nav nav-collapse">
                <li
                  class="{{ request()->routeIs('admin.withdraw.payment_method') && empty(request()->input('status')) ? 'active' : '' }}">
                  <a href="{{ route('admin.withdraw.payment_method', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Payment Methods') }}</span>
                  </a>
                </li>

                <li
                  class="{{ request()->routeIs('admin.withdraw.withdraw_request') && empty(request()->input('status')) ? 'active' : '' }}">
                  <a href="{{ route('admin.withdraw.withdraw_request', ['language' => $defaultLang->code]) }}">
                    <span class="sub-item">{{ __('Withdraw Requests') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- Transaction --}}
        @if (empty($admin->role) || (!empty($permissions) && in_array('Transaction', $permissions)))
          <li class="nav-item @if (request()->routeIs('admin.transcation')) active @endif">
            <a href="{{ route('admin.transcation') }}">
              <i class="fal fa-exchange-alt"></i>
              <p>{{ __('Transactions') }}</p>
            </a>
          </li>
        @endif

        {{-- vendor --}}
        @if (is_null($admin->role) || (!empty($permissions) && in_array('Vendors Management', $permissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.vendor_management.registered_vendor')) active
            @elseif (request()->routeIs('admin.vendor_management.add_vendor')) active
            @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
            @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
            @elseif (request()->routeIs('admin.vendor_management.settings')) active
            @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active @endif">
            <a data-toggle="collapse" href="#vendor">
              <i class="fal fa-user-chart"></i>
              <p>{{ __('Vendors Management') }}</p>
              <span class="caret"></span>
            </a>

            <div id="vendor"
              class="collapse
              @if (request()->routeIs('admin.vendor_management.registered_vendor')) show
              @elseif (request()->routeIs('admin.vendor_management.vendor_details')) show
              @elseif (request()->routeIs('admin.edit_management.vendor_edit')) show
              @elseif (request()->routeIs('admin.vendor_management.add_vendor')) show
              @elseif (request()->routeIs('admin.vendor_management.settings')) show
              @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) show @endif">
              <ul class="nav nav-collapse">
                <li class="@if (request()->routeIs('admin.vendor_management.settings')) active @endif">
                  <a href="{{ route('admin.vendor_management.settings') }}">
                    <span class="sub-item">{{ __('Settings') }}</span>
                  </a>
                </li>
                <li
                  class="@if (request()->routeIs('admin.vendor_management.registered_vendor')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor_details')) active
                  @elseif (request()->routeIs('admin.edit_management.vendor_edit')) active
                  @elseif (request()->routeIs('admin.vendor_management.vendor.change_password')) active @endif">
                  <a href="{{ route('admin.vendor_management.registered_vendor') }}">
                    <span class="sub-item">{{ __('Registered vendors') }}</span>
                  </a>
                </li>
                <li class="@if (request()->routeIs('admin.vendor_management.add_vendor')) active @endif">
                  <a href="{{ route('admin.vendor_management.add_vendor') }}">
                    <span class="sub-item">{{ __('Add vendor') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Users Management', $permissions)))
          {{-- Users Management --}}
          <li
            class="nav-item 
            @if (request()->routeIs('admin.register.user')) active
            @elseif (request()->routeIs('admin.register.create')) active
            @elseif (request()->routeIs('admin.register.user.edit')) active
            @elseif(request()->routeIs('register.user.view')) active
            @elseif(request()->routeIs('register.user.changePass')) active
            @elseif(request()->path() == 'admin/pushnotification/settings') active
            @elseif(request()->path() == 'admin/pushnotification/send') active
            @elseif(request()->path() == 'admin/subscribers') active
            @elseif(request()->path() == 'admin/mailsubscriber') active 
            @elseif (request()->routeIs('admin.user_management.push_notification.settings')) active
            @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) active @endif">
            <a data-toggle="collapse" href="#usersManagement">
              <i class="la flaticon-users"></i>
              <p>Users Management</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
              @if (request()->routeIs('admin.register.user')) show
              @elseif (request()->routeIs('admin.register.create')) show
              @elseif (request()->routeIs('admin.register.user.edit')) show
              @elseif(request()->routeIs('register.user.view')) show
              @elseif(request()->routeIs('register.user.changePass')) show
              @elseif(request()->path() == 'admin/pushnotification/settings') show
              @elseif(request()->path() == 'admin/pushnotification/send') show
              @elseif(request()->path() == 'admin/subscribers') show
              @elseif(request()->path() == 'admin/mailsubscriber') show
              @elseif (request()->routeIs('admin.user_management.push_notification.settings')) show
              @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif"
              id="usersManagement">
              <ul class="nav nav-collapse">
                {{-- Registered Users --}}
                <li
                  class="@if (request()->routeIs('admin.register.user')) active
                  @elseif(request()->routeIs('register.user.view')) active
                  @elseif(request()->routeIs('admin.register.user.edit')) active
                  @elseif(request()->routeIs('register.user.changePass')) active @endif">
                  <a href="{{ route('admin.register.user') }}">
                    <span class="sub-item">Registered Users</span>
                  </a>
                </li>
                <li class="@if (request()->routeIs('admin.register.create')) active @endif">
                  <a href="{{ route('admin.register.create') }}">
                    <span class="sub-item">Add User</span>
                  </a>
                </li>
                {{-- Subscribers --}}
                <li
                  class="@if (request()->path() == 'admin/subscribers') selected
                  @elseif(request()->path() == 'admin/mailsubscriber') selected @endif">
                  <a data-toggle="collapse" href="#subscribers">
                    <span class="sub-item">Subscribers</span>
                    <span class="caret"></span>
                  </a>
                  <div
                    class="collapse
                    @if (request()->path() == 'admin/subscribers') show
                    @elseif(request()->path() == 'admin/mailsubscriber') show @endif"
                    id="subscribers">
                    <ul class="nav nav-collapse subnav">
                      <li class="@if (request()->path() == 'admin/subscribers') active @endif">
                        <a href="{{ route('admin.subscriber.index') }}">
                          <span class="sub-item">Subscribers</span>
                        </a>
                      </li>
                      <li class="@if (request()->path() == 'admin/mailsubscriber') active @endif">
                        <a href="{{ route('admin.mailsubscriber') }}">
                          <span class="sub-item">Mail to Subscribers</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
                {{-- Push Notification --}}
                <li class="submenu">
                  <a data-toggle="collapse" href="#push_notification">
                    <span class="sub-item">{{ __('Push Notification') }}</span>
                    <span class="caret"></span>
                  </a>

                  <div id="push_notification"
                    class="collapse 
                    @if (request()->routeIs('admin.user_management.push_notification.settings')) show 
                    @elseif (request()->routeIs('admin.user_management.push_notification.notification_for_visitors')) show @endif">
                    <ul class="nav nav-collapse subnav">
                      <li
                        class="{{ request()->routeIs('admin.user_management.push_notification.settings') ? 'active' : '' }}">
                        <a href="{{ route('admin.user_management.push_notification.settings') }}">
                          <span class="sub-item">{{ __('Settings') }}</span>
                        </a>
                      </li>

                      <li
                        class="{{ request()->routeIs('admin.user_management.push_notification.notification_for_visitors') ? 'active' : '' }}">
                        <a href="{{ route('admin.user_management.push_notification.notification_for_visitors') }}">
                          <span class="sub-item">{{ __('Send Notification') }}</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
              </ul>
            </div>
          </li>
        @endif


        {{-- Support Ticket --}}
        @if (empty($admin->role) || (!empty($permissions) && in_array('Support Tickets', $permissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.support_ticket.setting')) active
            @elseif (request()->routeIs('admin.support_tickets')) active
            @elseif (request()->routeIs('admin.support_tickets.message')) active active @endif">
            <a data-toggle="collapse" href="#support_ticket">
              <i class="la flaticon-web-1"></i>
              <p>Support Tickets</p>
              <span class="caret"></span>
            </a>

            <div id="support_ticket"
              class="collapse
              @if (request()->routeIs('admin.support_ticket.setting')) show
              @elseif (request()->routeIs('admin.support_tickets')) show
              @elseif (request()->routeIs('admin.support_tickets.message')) show @endif">
              <ul class="nav nav-collapse">
                <li class="@if (request()->routeIs('admin.support_ticket.setting')) active @endif">
                  <a href="{{ route('admin.support_ticket.setting') }}">
                    <span class="sub-item">{{ __('Setting') }}</span>
                  </a>
                </li>
                <li
                  class="{{ request()->routeIs('admin.support_tickets') && empty(request()->input('status')) ? 'active' : '' }}">
                  <a href="{{ route('admin.support_tickets') }}">
                    <span class="sub-item">{{ __('All Tickets') }}</span>
                  </a>
                </li>
                <li
                  class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 1 ? 'active' : '' }}">
                  <a href="{{ route('admin.support_tickets', ['status' => 1]) }}">
                    <span class="sub-item">{{ __('Pending Tickets') }}</span>
                  </a>
                </li>
                <li
                  class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 2 ? 'active' : '' }}">
                  <a href="{{ route('admin.support_tickets', ['status' => 2]) }}">
                    <span class="sub-item">{{ __('Open Tickets') }}</span>
                  </a>
                </li>
                <li
                  class="{{ request()->routeIs('admin.support_tickets') && request()->input('status') == 3 ? 'active' : '' }}">
                  <a href="{{ route('admin.support_tickets', ['status' => 3]) }}">
                    <span class="sub-item">{{ __('Closed Tickets') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Home Page Sections', $permissions)))
          {{-- home page --}}
          <li
            class="nav-item @if (request()->routeIs('admin.home_page.hero.static_version')) active
            @elseif (request()->routeIs('admin.home_page.hero.slider_version')) active
            @elseif (request()->routeIs('admin.home_page.hero.create_slider')) active
            @elseif (request()->routeIs('admin.home_page.hero.edit_slider')) active
            @elseif (request()->routeIs('admin.home_page.hero.video_version')) active
            @elseif (request()->routeIs('admin.home_page.intro_section')) active
            @elseif (request()->routeIs('admin.home_page.intro_section.create_count_info')) active
            @elseif (request()->routeIs('admin.home_page.intro_section.edit_count_info')) active
            @elseif (request()->routeIs('admin.home_page.room_section')) active
            @elseif (request()->routeIs('admin.home_page.service_section')) active
            @elseif (request()->routeIs('admin.home_page.booking_section')) active
            @elseif (request()->routeIs('admin.home_page.package_section')) active
            @elseif (request()->routeIs('admin.home_page.facility_section')) active
            @elseif (request()->routeIs('admin.home_page.facility_section.create_facility')) active
            @elseif (request()->routeIs('admin.home_page.facility_section.edit_facility')) active
            @elseif (request()->routeIs('admin.home_page.testimonial_section')) active
            @elseif (request()->routeIs('admin.home_page.testimonial_section.create_testimonial')) active
            @elseif (request()->routeIs('admin.home_page.testimonial_section.edit_testimonial')) active
            @elseif (request()->routeIs('admin.home_page.brand_section')) active
            @elseif (request()->routeIs('admin.home_page.faq_section')) active
            @elseif (request()->routeIs('admin.home_page.blog_section')) active
            @elseif (request()->routeIs('admin.sections.index')) active @endif">
            <a data-toggle="collapse" href="#home_page">
              <i class="fal fa-layer-group"></i>
              <p>{{ __('Home Page Sections') }}</p>
              <span class="caret"></span>
            </a>
            <div id="home_page"
              class="collapse
              @if (request()->routeIs('admin.home_page.hero.static_version')) show
              @elseif (request()->routeIs('admin.home_page.hero.slider_version')) show
              @elseif (request()->routeIs('admin.home_page.hero.create_slider')) show
              @elseif (request()->routeIs('admin.home_page.hero.edit_slider')) show
              @elseif (request()->routeIs('admin.home_page.hero.video_version')) show
              @elseif (request()->routeIs('admin.home_page.intro_section')) show
              @elseif (request()->routeIs('admin.home_page.intro_section.create_count_info')) show
              @elseif (request()->routeIs('admin.home_page.intro_section.edit_count_info')) show
              @elseif (request()->routeIs('admin.home_page.room_section')) show
              @elseif (request()->routeIs('admin.home_page.service_section')) show
              @elseif (request()->routeIs('admin.home_page.booking_section')) show
              @elseif (request()->routeIs('admin.home_page.package_section')) show
              @elseif (request()->routeIs('admin.home_page.facility_section')) show
              @elseif (request()->routeIs('admin.home_page.facility_section.create_facility')) show
              @elseif (request()->routeIs('admin.home_page.facility_section.edit_facility')) show
              @elseif (request()->routeIs('admin.home_page.testimonial_section')) show
              @elseif (request()->routeIs('admin.home_page.testimonial_section.create_testimonial')) show
              @elseif (request()->routeIs('admin.home_page.testimonial_section.edit_testimonial')) show
              @elseif (request()->routeIs('admin.home_page.brand_section')) show
              @elseif (request()->routeIs('admin.home_page.faq_section')) show
              @elseif (request()->routeIs('admin.home_page.blog_section')) show
              @elseif (request()->routeIs('admin.sections.index')) show @endif">
              <ul class="nav nav-collapse">
                <li class="submenu">
                  <a data-toggle="collapse" href="#hero_section">
                    <span class="sub-item">{{ __('Hero Section') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div id="hero_section"
                    class="collapse
                    @if (request()->routeIs('admin.home_page.hero.static_version')) show
                    @elseif (request()->routeIs('admin.home_page.hero.slider_version')) show
                    @elseif (request()->routeIs('admin.home_page.hero.create_slider')) show
                    @elseif (request()->routeIs('admin.home_page.hero.edit_slider')) show
                    @elseif (request()->routeIs('admin.home_page.hero.video_version')) show @endif">
                    <ul class="nav nav-collapse subnav">
                      <li class="{{ request()->routeIs('admin.home_page.hero.static_version') ? 'active' : '' }}">
                        <a
                          href="{{ route('admin.home_page.hero.static_version') . '?language=' . $defaultLang->code }}">
                          <span class="sub-item">{{ __('Static Version') }}</span>
                        </a>
                      </li>
                      <li
                        class="@if (request()->routeIs('admin.home_page.hero.slider_version')) active
                        @elseif (request()->routeIs('admin.home_page.hero.create_slider')) active
                        @elseif (request()->routeIs('admin.home_page.hero.edit_slider')) active @endif">
                        <a
                          href="{{ route('admin.home_page.hero.slider_version') . '?language=' . $defaultLang->code }}">
                          <span class="sub-item">{{ __('Slider Version') }}</span>
                        </a>
                      </li>
                      <li class="{{ request()->routeIs('admin.home_page.hero.video_version') ? 'active' : '' }}">
                        <a
                          href="{{ route('admin.home_page.hero.video_version') . '?language=' . $defaultLang->code }}">
                          <span class="sub-item">{{ __('Video Version') }}</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li
                  class="@if (request()->routeIs('admin.home_page.intro_section')) active
                  @elseif (request()->routeIs('admin.home_page.intro_section.create_count_info')) active
                  @elseif (request()->routeIs('admin.home_page.intro_section.edit_count_info')) active @endif">
                  <a href="{{ route('admin.home_page.intro_section') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Intro & Counter Section') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.home_page.room_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.room_section') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Room Section') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.home_page.service_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.service_section') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Service Section') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.home_page.booking_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.booking_section') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Video Section') }}</span>
                  </a>
                </li>
                <li
                  class="{{ request()->routeIs('admin.home_page.package_section') ? 'active' : '' }} {{ $websiteInfo->theme_version == 'theme_one' ? '' : 'd-none' }}">
                  <a href="{{ route('admin.home_page.package_section') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Package Section') }}</span>
                  </a>
                </li>
                <li
                  class="@if (request()->routeIs('admin.home_page.facility_section')) active
                  @elseif (request()->routeIs('admin.home_page.facility_section.create_facility')) active
                  @elseif (request()->routeIs('admin.home_page.facility_section.edit_facility')) active @endif {{ $websiteInfo->theme_version == 'theme_one' ? '' : 'd-none' }}">
                  <a href="{{ route('admin.home_page.facility_section') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Facility Section') }}</span>
                  </a>
                </li>
                <li
                  class="@if (request()->routeIs('admin.home_page.testimonial_section')) active
                  @elseif (request()->routeIs('admin.home_page.testimonial_section.create_testimonial')) active
                  @elseif (request()->routeIs('admin.home_page.testimonial_section.edit_testimonial')) active @endif">
                  <a href="{{ route('admin.home_page.testimonial_section') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Testimonial Section') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.home_page.brand_section') ? 'active' : '' }}">
                  <a href="{{ route('admin.home_page.brand_section') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Brand Section') }}</span>
                  </a>
                </li>
                @if ($settings->theme_version == 'theme_two')
                  <li class="{{ request()->routeIs('admin.home_page.faq_section') ? 'active' : '' }}">
                    <a href="{{ route('admin.home_page.faq_section') . '?language=' . $defaultLang->code }}">
                      <span class="sub-item">{{ __('FAQ Section') }}</span>
                    </a>
                  </li>
                @endif
                <li
                  class="{{ request()->routeIs('admin.home_page.blog_section') ? 'active' : '' }} {{ $websiteInfo->theme_version == 'theme_two' ? '' : 'd-none' }}">
                  <a href="{{ route('admin.home_page.blog_section') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Blog Section') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.sections.index') ? 'active' : '' }}">
                  <a href="{{ route('admin.sections.index') }}">
                    <span class="sub-item">{{ __('Sections Hide / Show') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Footer', $permissions)))
          {{-- footer --}}
          <li
            class="nav-item @if (request()->routeIs('admin.footer.text')) active
            @elseif (request()->routeIs('admin.footer.quick_links')) active @endif">
            <a data-toggle="collapse" href="#footer">
              <i class="fal fa-shoe-prints"></i>
              <p>{{ __('Footer') }}</p>
              <span class="caret"></span>
            </a>
            <div id="footer"
              class="collapse
              @if (request()->routeIs('admin.footer.text')) show
              @elseif (request()->routeIs('admin.footer.quick_links')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.footer.text') ? 'active' : '' }}">
                  <a href="{{ route('admin.footer.text') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Footer Text') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.footer.quick_links') ? 'active' : '' }}">
                  <a href="{{ route('admin.footer.quick_links') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">Quick Links</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif


        @if (empty($admin->role) || (!empty($permissions) && in_array('Services Management', $permissions)))
          {{-- services --}}
          <li
            class="nav-item @if (request()->routeIs('admin.services_management')) active
            @elseif (request()->routeIs('admin.services_management.create_service')) active
            @elseif (request()->routeIs('admin.services_management.edit_service')) active @endif">
            <a href="{{ route('admin.services_management') }}">
              <i class="fal fa-concierge-bell"></i>
              <p>{{ __('Services Management') }}</p>
            </a>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Blogs Management', $permissions)))
          {{-- blogs --}}
          <li
            class="nav-item @if (request()->routeIs('admin.blogs_management.categories')) active
            @elseif (request()->routeIs('admin.blogs_management.blogs')) active
            @elseif (request()->routeIs('admin.blogs_management.create_blog')) active
            @elseif (request()->routeIs('admin.blogs_management.edit_blog')) active @endif">
            <a data-toggle="collapse" href="#blogs">
              <i class="la flaticon-chat-4"></i>
              <p>{{ __('Blogs Management') }}</p>
              <span class="caret"></span>
            </a>
            <div id="blogs"
              class="collapse
              @if (request()->routeIs('admin.blogs_management.categories')) show
              @elseif (request()->routeIs('admin.blogs_management.blogs')) show
              @elseif (request()->routeIs('admin.blogs_management.create_blog')) show
              @elseif (request()->routeIs('admin.blogs_management.edit_blog')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.blogs_management.categories') ? 'active' : '' }}">
                  <a href="{{ route('admin.blogs_management.categories') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">Categories</span>
                  </a>
                </li>
                <li
                  class="@if (request()->routeIs('admin.blogs_management.blogs')) active
                  @elseif (request()->routeIs('admin.blogs_management.create_blog')) active
                  @elseif (request()->routeIs('admin.blogs_management.edit_blog')) active @endif">
                  <a href="{{ route('admin.blogs_management.blogs') }}">
                    <span class="sub-item">Blogs</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Gallery Management', $permissions)))
          {{-- gallery --}}
          <li
            class="nav-item @if (request()->routeIs('admin.gallery_management.categories')) active
            @elseif (request()->routeIs('admin.gallery_management.images')) active @endif">
            <a data-toggle="collapse" href="#gallery">
              <i class="la flaticon-picture"></i>
              <p>{{ __('Gallery Management') }}</p>
              <span class="caret"></span>
            </a>
            <div id="gallery"
              class="collapse
              @if (request()->routeIs('admin.gallery_management.categories')) show
              @elseif (request()->routeIs('admin.gallery_management.images')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.gallery_management.categories') ? 'active' : '' }}">
                  <a href="{{ route('admin.gallery_management.categories') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">Categories</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.gallery_management.images') ? 'active' : '' }}">
                  <a href="{{ route('admin.gallery_management.images') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Images') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('FAQ Management', $permissions)))
          {{-- faq --}}
          <li class="nav-item {{ request()->routeIs('admin.faq_management') ? 'active' : '' }}">
            <a href="{{ route('admin.faq_management') . '?language=' . $defaultLang->code }}">
              <i class="la flaticon-round"></i>
              <p>{{ __('FAQ Management') }}</p>
            </a>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Custom Pages', $permissions)))
          {{-- Custom Pages --}}
          <li
            class="nav-item @if (request()->path() == 'admin/page/create') active
            @elseif(request()->path() == 'admin/pages') active
            @elseif(request()->path() == 'admin/page/paren/link') active
            @elseif(request()->is('admin/page/*/edit')) active @endif">
            <a data-toggle="collapse" href="#pages">
              <i class="la flaticon-file"></i>
              <p>Custom Pages</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
              @if (request()->path() == 'admin/page/create') show
              @elseif(request()->path() == 'admin/pages') show
              @elseif(request()->is('admin/page/*/edit')) show
              @elseif(request()->path() == 'admin/page/paren/link') show @endif"
              id="pages">
              <ul class="nav nav-collapse">
                <li class="@if (request()->path() == 'admin/page/create') active @endif">
                  <a href="{{ route('admin.page.create') }}">
                    <span class="sub-item">Create Page</span>
                  </a>
                </li>
                <li
                  class="@if (request()->path() == 'admin/pages') active
                  @elseif(request()->is('admin/page/*/edit')) active @endif">
                  <a href="{{ route('admin.page.index') }}">
                    <span class="sub-item">Pages</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- Announcement Popup --}}
        @if (empty($admin->role) || (!empty($permissions) && in_array('Announcement Popup', $permissions)))
          <li
            class="nav-item @if (request()->path() == 'admin/popup/create') active
            @elseif(request()->path() == 'admin/popup/types') active
            @elseif(request()->is('admin/popup/*/edit')) active
            @elseif(request()->path() == 'admin/popups') active @endif">
            <a data-toggle="collapse" href="#announcementPopup">
              <i class="fas fa-bullhorn"></i>
              <p>Announcement Popup</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
              @if (request()->path() == 'admin/popup/create') show
              @elseif(request()->path() == 'admin/popup/types') show
              @elseif(request()->path() == 'admin/popups') show
              @elseif(request()->is('admin/popup/*/edit')) show @endif"
              id="announcementPopup">
              <ul class="nav nav-collapse">
                <li
                  class="@if (request()->path() == 'admin/popup/types') active
                  @elseif(request()->path() == 'admin/popup/create') active @endif">
                  <a href="{{ route('admin.popup.types') }}">
                    <span class="sub-item">Add Popup</span>
                  </a>
                </li>
                <li
                  class="@if (request()->path() == 'admin/popups') active
                  @elseif(request()->is('admin/popup/*/edit')) active @endif">
                  <a href="{{ route('admin.popup.index') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">Popups</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Payment Gateways', $permissions)))
          {{-- payment gateways --}}
          <li
            class="nav-item @if (request()->routeIs('admin.payment_gateways.online_gateways')) active
            @elseif (request()->routeIs('admin.payment_gateways.offline_gateways')) active @endif">
            <a data-toggle="collapse" href="#payment_gateways">
              <i class="la flaticon-paypal"></i>
              <p>{{ __('Payment Gateways') }}</p>
              <span class="caret"></span>
            </a>
            <div id="payment_gateways"
              class="collapse
              @if (request()->routeIs('admin.payment_gateways.online_gateways')) show
              @elseif (request()->routeIs('admin.payment_gateways.offline_gateways')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.payment_gateways.online_gateways') ? 'active' : '' }}">
                  <a href="{{ route('admin.payment_gateways.online_gateways') }}">
                    <span class="sub-item">{{ __('Online Gateways') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.payment_gateways.offline_gateways') ? 'active' : '' }}">
                  <a href="{{ route('admin.payment_gateways.offline_gateways') }}">
                    <span class="sub-item">{{ __('Offline Gateways') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Theme & Home', $permissions)))
          {{-- Theme & Home --}}
          <li class="nav-item @if (request()->routeIs('admin.theme.version')) active @endif">
            <a href="{{ route('admin.theme.version') }}">
              <i class="la flaticon-file"></i>
              <p>{{ __('Theme & Home') }}</p>
            </a>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Settings', $permissions)))
          {{-- basic settings --}}
          <li
            class="nav-item @if (request()->path() == 'admin/preloader') active
            @elseif (request()->routeIs('admin.basic_settings.general_settings')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_from_admin')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) active
            @elseif (request()->routeIs('admin.basic_settings.mail_templates')) active
            @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) active
            @elseif (request()->routeIs('admin.basic_settings.social_links')) active
            @elseif (request()->routeIs('admin.basic_settings.edit_social_link')) active
            @elseif (request()->routeIs('admin.basic_settings.breadcrumb')) active
            @elseif (request()->routeIs('admin.basic_settings.page_headings')) active
            @elseif (request()->routeIs('admin.basic_settings.scripts')) active
            @elseif (request()->routeIs('admin.basic_settings.seo')) active
            @elseif (request()->routeIs('admin.basic_settings.maintenance_mode')) active
            @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) active
            @elseif (request()->routeIs('admin.basic_settings.footer_logo')) active @endif">
            <a data-toggle="collapse" href="#basic_settings">
              <i class="la flaticon-settings"></i>
              <p>{{ __('Settings') }}</p>
              <span class="caret"></span>
            </a>
            <div id="basic_settings"
              class="collapse
              @if (request()->path() == 'admin/preloader') show
              @elseif (request()->routeIs('admin.basic_settings.general_settings')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_from_admin')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) show
              @elseif (request()->routeIs('admin.basic_settings.mail_templates')) show
              @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) show
              @elseif (request()->routeIs('admin.basic_settings.social_links')) show
              @elseif (request()->routeIs('admin.basic_settings.edit_social_link')) show
              @elseif (request()->routeIs('admin.basic_settings.breadcrumb')) show
              @elseif (request()->routeIs('admin.basic_settings.page_headings')) show
              @elseif (request()->routeIs('admin.basic_settings.scripts')) show
              @elseif (request()->routeIs('admin.basic_settings.seo')) show
              @elseif (request()->routeIs('admin.basic_settings.maintenance_mode')) show
              @elseif (request()->routeIs('admin.basic_settings.cookie_alert')) show
              @elseif (request()->routeIs('admin.basic_settings.footer_logo')) show @endif">
              <ul class="nav nav-collapse">
                <li class="{{ request()->routeIs('admin.basic_settings.general_settings') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.general_settings') }}">
                    <span class="sub-item">{{ __('General Settings') }}</span>
                  </a>
                </li>

                <li
                  class="submenu @if (request()->routeIs('admin.basic_settings.mail_from_admin')) selected
                  @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) selected
                  @elseif (request()->routeIs('admin.basic_settings.mail_templates')) selected
                  @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) selected @endif">
                  <a data-toggle="collapse" href="#mail_settings">
                    <span class="sub-item">{{ __('Email Settings') }}</span>
                    <span class="caret"></span>
                  </a>
                  <div id="mail_settings"
                    class="collapse
                    @if (request()->routeIs('admin.basic_settings.mail_from_admin')) show
                    @elseif (request()->routeIs('admin.basic_settings.mail_to_admin')) show
                    @elseif (request()->routeIs('admin.basic_settings.mail_templates')) show
                    @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) show @endif">
                    <ul class="nav nav-collapse subnav">
                      <li class="{{ request()->routeIs('admin.basic_settings.mail_from_admin') ? 'active' : '' }}">
                        <a href="{{ route('admin.basic_settings.mail_from_admin') }}">
                          <span class="sub-item">{{ __('Mail From Admin') }}</span>
                        </a>
                      </li>
                      <li class="{{ request()->routeIs('admin.basic_settings.mail_to_admin') ? 'active' : '' }}">
                        <a href="{{ route('admin.basic_settings.mail_to_admin') }}">
                          <span class="sub-item">{{ __('Mail To Admin') }}</span>
                        </a>
                      </li>
                      <li
                        class="@if (request()->routeIs('admin.basic_settings.mail_templates')) active
                        @elseif (request()->routeIs('admin.basic_settings.edit_mail_template')) active @endif">
                        <a href="{{ route('admin.basic_settings.mail_templates') }}">
                          <span class="sub-item">{{ __('Mail Templates') }}</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </li>
                <li
                  class="@if (request()->routeIs('admin.basic_settings.social_links')) active 
                  @elseif (request()->routeIs('admin.basic_settings.edit_social_link')) active @endif">
                  <a href="{{ route('admin.basic_settings.social_links') }}">
                    <span class="sub-item">{{ __('Social Links') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.basic_settings.breadcrumb') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.breadcrumb') }}">
                    <span class="sub-item">{{ __('Breadcrumb') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.basic_settings.page_headings') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.page_headings') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Page Headings') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.basic_settings.scripts') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.scripts') }}">
                    <span class="sub-item">{{ __('Plugins') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.basic_settings.seo') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.seo') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('SEO Informations') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.basic_settings.maintenance_mode') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.maintenance_mode') }}">
                    <span class="sub-item">{{ __('Maintenance Mode') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.basic_settings.cookie_alert') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.cookie_alert') . '?language=' . $defaultLang->code }}">
                    <span class="sub-item">{{ __('Cookie Alert') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('admin.basic_settings.footer_logo') ? 'active' : '' }}">
                  <a href="{{ route('admin.basic_settings.footer_logo') }}">
                    <span class="sub-item">{{ __('Footer Logo') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Language Management', $permissions)))
          {{-- languages --}}
          <li
            class="nav-item @if (request()->routeIs('admin.languages')) active
            @elseif (request()->routeIs('admin.languages.edit_keyword')) active @endif">
            <a href="{{ route('admin.languages') }}">
              <i class="la flaticon-chat-8"></i>
              <p>{{ __('Language Management') }}</p>
            </a>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Admins Management', $permissions)))
          {{-- Admins Management --}}
          <li
            class="nav-item @if (request()->path() == 'admin/roles') active
            @elseif(request()->is('admin/role/*/permissions/manage')) active
            @elseif(request()->path() == 'admin/users') active
            @elseif(request()->is('admin/user/*/edit')) active @endif">
            <a data-toggle="collapse" href="#adminsManagement">
              <i class="fas fa-users-cog"></i>
              <p>Admins Management</p>
              <span class="caret"></span>
            </a>
            <div
              class="collapse
              @if (request()->path() == 'admin/roles') show
              @elseif(request()->is('admin/role/*/permissions/manage')) show
              @elseif(request()->path() == 'admin/users') show
              @elseif(request()->is('admin/user/*/edit')) show @endif"
              id="adminsManagement">
              <ul class="nav nav-collapse">
                <li
                  class="@if (request()->path() == 'admin/roles') active
                  @elseif(request()->is('admin/role/*/permissions/manage')) active @endif">
                  <a href="{{ route('admin.role.index') }}">
                    <span class="sub-item">Roles & Permissions</span>
                  </a>
                </li>
                <li
                  class="@if (request()->path() == 'admin/users') active
                  @elseif(request()->is('admin/user/*/edit')) active @endif">
                  <a href="{{ route('admin.user.index') }}">
                    <span class="sub-item">Admins</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        {{-- QR Code Builder --}}
        @if (empty($admin->role) || (!empty($permissions) && in_array('QR Builder', $permissions)))
          <li
            class="nav-item @if (request()->routeIs('admin.qrcode')) active
            @elseif(request()->routeIs('admin.qrcode.index')) active @endif">
            <a data-toggle="collapse" href="#qrcode">
              <i class="fas fa-qrcode"></i>
              <p>{{ __('QR Codes') }}</p>
              <span class="caret"></span>
            </a>
            <div id="qrcode"
              class="collapse
              @if (request()->routeIs('admin.qrcode')) show
              @elseif(request()->routeIs('admin.qrcode.index')) show @endif">
              <ul class="nav nav-collapse">
                <li class="@if (request()->routeIs('admin.qrcode')) active @endif">
                  <a href="{{ route('admin.qrcode') }}">
                    <span class="sub-item">{{ __('Generate QR Code') }}</span>
                  </a>
                </li>
                <li class="@if (request()->routeIs('admin.qrcode.index')) active @endif">
                  <a href="{{ route('admin.qrcode.index') }}">
                    <span class="sub-item">{{ __('Saved QR Codes') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif

        @if (empty($admin->role) || (!empty($permissions) && in_array('Sitemap', $permissions)))
          {{-- Sitemap --}}
          <li class="nav-item @if (request()->path() == 'admin/sitemap') active @endif">
            <a href="{{ route('admin.sitemap.index') . '?language=' . $defaultLang->code }}">
              <i class="fa fa-sitemap"></i>
              <p>Sitemap</p>
            </a>
          </li>
        @endif

        {{-- Cache Clear --}}
        <li class="nav-item">
          <a href="{{ route('admin.cache.clear') }}">
            <i class="la flaticon-close"></i>
            <p>Clear Cache</p>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
