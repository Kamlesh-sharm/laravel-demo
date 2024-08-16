<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntroCountInfo extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'icon',
    'title',
    'amount',
    'serial_number'
  ];

  public function countInfoLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
