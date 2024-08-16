<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'facility_icon',
    'facility_title',
    'facility_text'
  ];

  public function facilityLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
