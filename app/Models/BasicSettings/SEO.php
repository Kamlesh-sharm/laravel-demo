<?php

namespace App\Models\BasicSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
  use HasFactory;

  protected $table = 'seos';

  protected $fillable = [
    'language_id',
    'meta_keyword_home',
    'meta_description_home',
    'meta_keyword_blogs',
    'meta_description_blogs',
    'meta_keyword_about_us',
    'meta_description_about_us',
    'meta_keyword_contact_us',
    'meta_description_contact_us',
    'meta_keyword_gallery',
    'meta_description_gallery',
    'meta_keyword_faq',
    'meta_description_faq',
    'meta_keyword_packages',
    'meta_description_packages',
    'meta_keyword_rooms',
    'meta_description_rooms',
    'meta_keyword_services',
    'meta_description_services',
    'meta_keyword_error_page',
    'meta_description_error_page',
    'meta_keyword_registration',
    'meta_description_registration',
    'meta_keyword_login',
    'meta_description_login',
    'meta_keyword_forget_password',
    'meta_description_forget_password',
    'meta_keyword_vendor_registration',
    'meta_description_vendor_registration',
    'meta_keyword_vendor_login',
    'meta_description_vendor_login',
    'meta_keyword_vendor_forget_password',
    'meta_description_vendor_forget_password',
    'meta_keyword_vendors',
    'meta_description_vendors'
  ];

  public function seoLanguage()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
