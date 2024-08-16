<?php

namespace App\Models\PackageManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageLocation extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'package_id',
    'name',
    'latitude',
    'longitude'
  ];

  public function locationLang()
  {
    return $this->belongsTo('App\Models\Language');
  }

  public function packageInfo()
  {
    return $this->belongsTo('App\Models\PackageManagement\Package', 'package_id', 'id');
  }
}
