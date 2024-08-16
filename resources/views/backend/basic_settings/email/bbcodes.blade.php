<table class="table table-striped mb-5 bbcodes-table border-1px-black">
  <thead>
    <tr>
      <th scope="col">{{ __('Short Code') }}</th>
      <th scope="col">{{ __('Meaning') }}</th>
    </tr>
  </thead>
  <tbody>
    @if ($templateInfo->mail_type == 'balance add' || $templateInfo->mail_type == 'balance subtract')
      <tr>
        <td>
          {username}
        </td>
        <th scope="row">
          {{ __('Username') }}
        </th>
      </tr>
      <tr>
        <td>
          {transaction_id}
        </td>
        <th scope="row">
          {{ __('Transaction Id') }}
        </th>
      </tr>
      <tr>
        <td>
          {current_balance}
        </td>
        <th scope="row">
          {{ __('Current Balance of Vendor') }}
        </th>
      </tr>
    @else
      @if ($templateInfo->mail_type != 'vendor added')
        <tr>
          <td>
            {customer_name}
          </td>
          <th scope="row">
            {{ __('Customer Name') }}
          </th>
        </tr>
      @endif
    @endif

    @if ($templateInfo->mail_type == 'room booking')
      <tr>
        <td>
          {booking_number}
        </td>
        <th scope="row">
          {{ __('Booking Number') }}
        </th>
      </tr>
      <tr>
        <td>
          {booking_date}
        </td>
        <th scope="row">
          {{ __('Booking Date') }}
        </th>
      </tr>
      <tr>
        <td>
          {number_of_night}
        </td>
        <th scope="row">
          {{ __('Number of Nights') }}
        </th>
      </tr>
      <tr>
        <td>
          {check_in_date}
        </td>
        <th scope="row">
          {{ __('Check in Date') }}
        </th>
      </tr>
      <tr>
        <td>
          {check_out_date}
        </td>
        <th scope="row">
          {{ __('Check out Date') }}
        </th>
      </tr>
      <tr>
        <td>
          {number_of_guests}
        </td>
        <th scope="row">
          {{ __('Number of Guests') }}
        </th>
      </tr>
      <tr>
        <td>
          {room_name}
        </td>
        <th scope="row">
          {{ __('Room Name') }}
        </th>
      </tr>
      <tr>
        <td>
          {room_rent}
        </td>
        <th scope="row">
          {{ __('Room Rent') }}
        </th>
      </tr>
      <tr>
        <td>
          {room_type}
        </td>
        <th scope="row">
          {{ __('Room Type') }}
        </th>
      </tr>
      <tr>
        <td>
          {room_amenities}
        </td>
        <th scope="row">
          {{ __('Room Amenities') }}
        </th>
      </tr>
    @endif


    @if ($templateInfo->mail_type == 'package booking')
      <tr>
        <td>
          {booking_number}
        </td>
        <th scope="row">
          {{ __('Booking Number') }}
        </th>
      </tr>
      <tr>
        <td>
          {package_name}
        </td>
        <th scope="row">
          {{ __('Package Name') }}
        </th>
      </tr>
      <tr>
        <td>
          {package_price}
        </td>
        <th scope="row">
          {{ __('Package Price') }}
        </th>
      </tr>
      <tr>
        <td>
          {number_of_visitors}
        </td>
        <th scope="row">
          {{ __('Number of Visitors') }}
        </th>
      </tr>
    @endif

    @if ($templateInfo->mail_type == 'verify email')
      <tr>
        <td>
          {customer_username}
        </td>
        <th scope="row">
          {{ __('Username') }}
        </th>
      </tr>
      <tr>
        <td>
          {verification_link}
        </td>
        <th scope="row">
          {{ __('Verification Link') }}
        </th>
      </tr>
    @endif

    @if ($templateInfo->mail_type == 'reset password')
      <tr>
        <td>
          {click_here}
        </td>
        <th scope="row">
          {{ __('Password Reset Button') }}
        </th>
      </tr>
    @endif


    @if ($templateInfo->mail_type == 'withdraw approve')
      <tr>
        <td>{withdraw_amount}</td>
        <td scope="row">{{ __('Total Withdraw Amount') }}</td>
      </tr>
      <tr>
        <td>{charge}</td>
        <td scope="row">{{ __('Total Charge of Withdraw') }}</td>
      </tr>
      <tr>
        <td>{payable_amount}</td>
        <td scope="row">{{ __('Total Payable Amount') }}</td>
      </tr>

      <tr>
        <td>{withdraw_method}</td>
        <td scope="row">{{ __('Method Name of Withdraw') }}</td>
      </tr>
    @endif

    @if ($templateInfo->mail_type == 'withdraw-approve' || $templateInfo->mail_type == 'withdraw rejected')
      <tr>
        <td>{vendor_username}</td>
        <td scope="row">{{ __('Username of the vendor') }}</td>
      </tr>
      <tr>
        <td>{withdraw_id}</td>
        <td scope="row">{{ __('Withdraw Id') }}</td>
      </tr>
      <tr>
        <td>{current_balance}</td>
        <td scope="row">{{ __('Current Balance of Vendor') }}</td>
      </tr>
    @endif
    @if ($templateInfo->mail_type == 'vendor added')
      <tr>
        <td>{username}</td>
        <td scope="row">{{ __('Username of the vendor') }}</td>
      </tr>
      <tr>
        <td>{password}</td>
        <td scope="row">{{ __('Password of the vendor') }}</td>
      </tr>
    @endif

    <tr>
      <td>
        {website_title}
      </td>
      <th scope="row">
        {{ __('Website Title') }}
      </th>
    </tr>

  </tbody>
</table>
