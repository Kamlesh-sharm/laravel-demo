<?php

namespace App\Http\Requests;

use App\Rules\IsRoomAvailableRule;
use Illuminate\Foundation\Http\FormRequest;

class RoomBookingRequest extends FormRequest
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
    $ruleArray = [
      'dates' => [
        'required',
        new IsRoomAvailableRule($this->room_id)
      ],
      'nights' => 'required',
      'guests' => 'required|numeric|min:1',
      'customer_name' => 'required',
      'customer_phone' => 'required',
      'customer_email' => 'required|email:rfc,dns'
    ];

    if ($this->paymentType == 'stripe') {
      $ruleArray['card_number'] = 'required';
      $ruleArray['cvc_number'] = 'required';
      $ruleArray['expiry_month'] = 'required';
      $ruleArray['expiry_year'] = 'required';
    }

    return $ruleArray;
  }
}
