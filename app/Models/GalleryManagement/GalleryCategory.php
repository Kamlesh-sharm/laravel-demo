<?php

namespace App\Models\GalleryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryCategory extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'name',
    'status',
    'serial_number'
  ];

  public function galleryCategoryLang()
  {
    return $this->belongsTo('App\Models\Language');
  }

  public function galleryImgList()
  {
    return $this->hasMany('App\Models\GalleryManagement\Gallery');
  }
}
