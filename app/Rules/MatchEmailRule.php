<?php

namespace App\Rules;

use App\Models\Admin;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Contracts\Validation\Rule;

class MatchEmailRule implements Rule
{
  public $personType;

  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct($role)
  {
    // here, $role variable defines whether it is admin or user
    $this->personType = $role;
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    if ($this->personType == 'admin') {
      $adminEmail = Admin::where('email', $value)->first();

      if (is_null($adminEmail)) {
        return false;
      } else {
        return true;
      }
    } else if ($this->personType == 'user') {
      $userEmail = User::where('email', $value)->first();

      if (is_null($userEmail)) {
        return false;
      } else {
        return true;
      }
    } else if ($this->personType == 'vendor') {
      $vendorEmail = Vendor::where('email', $value)->first();

      if (is_null($vendorEmail)) {
        return false;
      } else {
        return true;
      }
    }
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'This email does not exist!';
  }
}
