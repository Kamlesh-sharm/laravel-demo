<?php

namespace App\Models\BasicSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieAlert extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'cookie_alert_status',
    'cookie_alert_btn_text',
    'cookie_alert_text'
  ];

  public function cookieAlertLanguage()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
