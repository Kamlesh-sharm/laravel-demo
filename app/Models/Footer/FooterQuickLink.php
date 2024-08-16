<?php

namespace App\Models\Footer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterQuickLink extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'title',
    'url',
    'serial_number'
  ];

  public function quickLinkLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
