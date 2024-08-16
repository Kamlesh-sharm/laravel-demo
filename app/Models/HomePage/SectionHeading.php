<?php

namespace App\Models\HomePage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionHeading extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'room_section_title',
    'room_section_subtitle',
    'room_section_text',
    'service_section_title',
    'service_section_subtitle',
    'booking_section_title',
    'booking_section_subtitle',
    'booking_section_button',
    'booking_section_button_url',
    'booking_section_video_url',
    'package_section_title',
    'package_section_subtitle',
    'facility_section_title',
    'facility_section_subtitle',
    'facility_section_image',
    'testimonial_section_title',
    'testimonial_section_subtitle',
    'testimonial_section_image',
    'faq_section_title',
    'faq_section_subtitle',
    'faq_section_image',
    'blog_section_title',
    'blog_section_subtitle'
  ];

  public function headingLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
