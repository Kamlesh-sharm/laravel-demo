<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'brand_img',
    'brand_url',
    'serial_number'
  ];

  public function brandLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
