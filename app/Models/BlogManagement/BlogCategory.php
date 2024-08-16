<?php

namespace App\Models\BlogManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'name',
    'status',
    'serial_number'
  ];

  public function blogCategoryLang()
  {
    return $this->belongsTo('App\Models\Language');
  }

  public function blogContentList()
  {
    return $this->hasMany('App\Models\BlogManagement\BlogContent');
  }
}
