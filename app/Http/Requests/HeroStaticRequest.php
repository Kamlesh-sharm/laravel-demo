<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeroStaticRequest extends FormRequest
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
      'title' => 'required',
      'subtitle' => 'required',
      'btn_name' => 'required',
      'btn_url' => 'required'
    ];
  }

  public function messages()
  {
    return [
      'btn_name.required' => 'The button name field is required.',
      'btn_url.required' => 'The button url field is required.'
    ];
  }
}
