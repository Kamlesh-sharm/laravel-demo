<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserProfileUpdateRequest extends FormRequest
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
    return [
      'user_image' => 'mimes:jpg,jpeg,png',
      'first_name' => 'required',
      'last_name' => 'required',
      'username' => [
        'required',
        'max:255',
        Rule::unique('users')->ignore($this->id)
      ],
      'contact_number' => 'required|numeric',
      'address' => 'required',
      'city' => 'required',
      'state' => 'required',
      'country' => 'required'
    ];
  }
}
