<?php

namespace App\Models\PackageManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageReview extends Model
{
  use HasFactory;

  protected $fillable = ['user_id', 'vendor_id', 'package_id', 'rating', 'comment'];

  public function packageReviewedByUser()
  {
    return $this->belongsTo('App\Models\User', 'user_id', 'id');
  }

  public function reviewOfPackage()
  {
    return $this->belongsTo('App\Models\PackageManagement\Package');
  }
}
