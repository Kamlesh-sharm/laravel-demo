<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroStatic extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'img',
    'title',
    'subtitle',
    'btn_name',
    'btn_url'
  ];

  public function staticVersionLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
