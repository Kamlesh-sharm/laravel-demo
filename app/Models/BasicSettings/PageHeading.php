<?php

namespace App\Models\BasicSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageHeading extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'blogs_title',
    'about_us_title',
    'contact_us_title',
    'faqs_title',
    'gallery_title',
    'rooms_title',
    'services_title',
    'packages_title',
    'error_page_title'
  ];

  public function headingLanguage()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
