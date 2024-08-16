<?php

namespace App\Models\PackageManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageContent extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'package_category_id',
    'package_id',
    'title',
    'slug',
    'description',
    'meta_keywords',
    'meta_description'
  ];

  public function packageCategory()
  {
    return $this->belongsTo('App\Models\PackageManagement\PackageCategory');
  }

  public function package()
  {
    return $this->belongsTo('App\Models\PackageManagement\Package');
  }

  public function packageContentLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
