<div class="col-lg-3">
  <div class="user-sidebar">
    <ul class="links">
      <li class="{{ request()->routeIs('user.dashboard') ? 'active-menu' : '' }}">
        <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
      </li>

      <li
        class="{{ request()->routeIs('user.room_bookings') || request()->routeIs('user.room_booking_details') ? 'active-menu' : '' }}">
        <a href="{{ route('user.room_bookings') }}">{{ __('Room Bookings') }}</a>
      </li>

      <li
        class="{{ request()->routeIs('user.package_bookings') || request()->routeIs('user.package_booking_details') ? 'active-menu' : '' }}">
        <a href="{{ route('user.package_bookings') }}">{{ __('Package Bookings') }}</a>
      </li>

      @php
        $support_status = DB::table('support_ticket_statuses')->first();
      @endphp
      @if ($support_status->support_ticket_status == 'active')
        <li
          class="{{ request()->routeIs('user.support_tickert') || request()->routeIs('user.support_tickert.create') || request()->routeIs('user.support_ticket.message') ? 'active-menu' : '' }}">
          <a href="{{ route('user.support_tickert') }}">{{ __('Support Ticket') }}</a>
        </li>
      @endif

      <li class="{{ request()->routeIs('user.edit_profile') ? 'active-menu' : '' }}">
        <a href="{{ route('user.edit_profile') }}">{{ __('Edit Profile') }}</a>
      </li>

      <li class="{{ request()->routeIs('user.change_password') ? 'active-menu' : '' }}">
        <a href="{{ route('user.change_password') }}">{{ __('Change Password') }}</a>
      </li>

      <li><a href="{{ route('user.logout') }}">{{ __('Logout') }}</a></li>
    </ul>
  </div>
</div>
