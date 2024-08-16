<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntroSection extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'intro_img',
    'intro_primary_title',
    'intro_secondary_title',
    'intro_text'
  ];

  public function introLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
