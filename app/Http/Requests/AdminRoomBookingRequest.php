<?php

namespace App\Http\Requests;

use App\Rules\IsRoomAvailableRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminRoomBookingRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    if ($this->filled('booking_id')) {
      $booking_id = $this->booking_id;
    } else {
      $booking_id = null;
    }

    return [
      'dates' => [
        'required',
        new IsRoomAvailableRule($this->room_id, $booking_id)
      ],
      'nights' => 'required',
      'guests' => 'required|numeric|min:1',
      'customer_name' => 'required',
      'customer_phone' => 'required',
      'customer_email' => 'required|email:rfc,dns',
      'payment_method' => 'required',
    ];
  }

  /**
   * Get the validation messages that apply to the request.
   *
   * @return array
   */
  public function messages()
  {
    return [
      'guests.min' => 'The guests must be at least 1 person.'
    ];
  }
}
