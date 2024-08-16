<?php

namespace App\Models\GalleryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
  use HasFactory;

  protected $table = 'gallery_images';

  protected $fillable = [
    'language_id',
    'gallery_category_id',
    'gallery_img',
    'title',
    'serial_number'
  ];

  public function galleryImgLang()
  {
    return $this->belongsTo('App\Models\Language');
  }

  public function galleryCategory()
  {
    return $this->belongsTo('App\Models\GalleryManagement\GalleryCategory', 'gallery_category_id', 'id');
  }
}
