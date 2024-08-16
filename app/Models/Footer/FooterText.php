<?php

namespace App\Models\Footer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterText extends Model
{
  use HasFactory;

  protected $fillable = ['language_id', 'about_company', 'copyright_text'];

  public function footerTextLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
