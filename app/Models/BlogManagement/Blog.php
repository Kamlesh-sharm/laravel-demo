<?php

namespace App\Models\BlogManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
  use HasFactory;

  protected $fillable = ['blog_img', 'serial_number'];

  public function blogContent()
  {
    return $this->hasMany('App\Models\BlogManagement\BlogContent');
  }
}
