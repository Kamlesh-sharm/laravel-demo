<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageHeadingRequest extends FormRequest
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
      'blogs_title' => 'required',
      'contact_us_title' => 'required',
      'faqs_title' => 'required',
      'gallery_title' => 'required',
      'rooms_title' => 'required',
      'services_title' => 'required',
      'packages_title' => 'required',
      'error_page_title' => 'required'
    ];
  }
}
