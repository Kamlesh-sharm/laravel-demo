<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
  use HasFactory;

  protected $fillable = ['name', 'code', 'direction', 'is_default'];

  public function pageName()
  {
    return $this->hasOne('App\Models\BasicSettings\PageHeading');
  }

  public function seoInfo()
  {
    return $this->hasOne('App\Models\BasicSettings\SEO');
  }

  public function cookieAlertInfo()
  {
    return $this->hasOne('App\Models\BasicSettings\CookieAlert');
  }

  public function staticVersion()
  {
    return $this->hasOne('App\Models\HomePage\HeroStatic');
  }

  public function sliderVersion()
  {
    return $this->hasMany('App\Models\HomePage\HeroSlider');
  }

  public function videoVersion()
  {
    return $this->hasOne('App\Models\HomePage\HeroVideo');
  }

  public function introSec()
  {
    return $this->hasOne('App\Models\HomePage\IntroSection');
  }

  public function introSecCountInfo()
  {
    return $this->hasMany('App\Models\HomePage\IntroCountInfo');
  }

  public function sectionHeadingInfo()
  {
    return $this->hasOne('App\Models\HomePage\SectionHeading');
  }

  public function facility()
  {
    return $this->hasMany('App\Models\HomePage\Facility');
  }

  public function testimonial()
  {
    return $this->hasMany('App\Models\HomePage\Testimonial');
  }

  public function brand()
  {
    return $this->hasMany('App\Models\HomePage\Brand');
  }

  public function serviceDetails()
  {
    return $this->hasMany('App\Models\ServiceManagement\ServiceContent');
  }

  public function blogCategory()
  {
    return $this->hasMany('App\Models\BlogManagement\BlogCategory');
  }

  public function blogDetails()
  {
    return $this->hasMany('App\Models\BlogManagement\BlogContent');
  }

  public function faq()
  {
    return $this->hasMany('App\Models\FAQ');
  }

  public function galleryCategory()
  {
    return $this->hasMany('App\Models\GalleryManagement\GalleryCategory');
  }

  public function galleryImg()
  {
    return $this->hasMany('App\Models\GalleryManagement\Gallery');
  }

  public function footerText()
  {
    return $this->hasOne('App\Models\Footer\FooterText');
  }

  public function footerQuickLink()
  {
    return $this->hasMany('App\Models\Footer\FooterQuickLink');
  }

  public function amenity()
  {
    return $this->hasMany('App\Models\RoomManagement\RoomAmenity');
  }

  public function roomCategory()
  {
    return $this->hasMany('App\Models\RoomManagement\RoomCategory');
  }

  public function roomDetails()
  {
    return $this->hasMany('App\Models\RoomManagement\RoomContent');
  }

  public function packageCategory()
  {
    return $this->hasMany('App\Models\PackageManagement\PackageCategory');
  }

  public function packageDetails()
  {
    return $this->hasMany('App\Models\PackageManagement\PackageContent');
  }

  public function packageLocation()
  {
    return $this->hasMany('App\Models\PackageManagement\PackageLocation');
  }

  public function packagePlan()
  {
    return $this->hasMany('App\Models\PackageManagement\PackagePlan');
  }

  public function page_contents() {
      return $this->hasMany('App\Models\PageContent');
  }

  public function popups() {
      return $this->hasMany('App\Models\Popup');
  }
}
