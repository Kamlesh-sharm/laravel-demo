<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'client_image',
    'client_name',
    'client_designation',
    'comment',
    'serial_number'
  ];

  public function testimonialLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
