<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
  use HasFactory;

  protected $table = 'faqs';

  protected $fillable = [
    'language_id',
    'question',
    'answer',
    'serial_number'
  ];

  public function faqLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
