<?php

namespace App\Models\PackageManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageCategory extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'name',
    'status',
    'serial_number'
  ];

  public function packageCategoryLang()
  {
    return $this->belongsTo('App\Models\Language');
  }

  public function packageContentList()
  {
    return $this->hasMany('App\Models\PackageManagement\PackageContent');
  }
}
