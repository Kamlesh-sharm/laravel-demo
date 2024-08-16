<?php

namespace App\Models\PackageManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagePlan extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'package_id',
    'day_number',
    'start_time',
    'end_time',
    'title',
    'plan'
  ];

  public function planLang()
  {
    return $this->belongsTo('App\Models\Language');
  }

  public function ownedByPackage()
  {
    return $this->belongsTo('App\Models\PackageManagement\Package');
  }
}
