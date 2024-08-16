<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounterInfoRequest extends FormRequest
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

      'language_id' => request()->isMethod('POST') ? 'required' : '',
      'icon' => 'required',
      'title' => 'required',
      'amount' => 'required',
      'serial_number' => 'required'
    ];
  }
}
