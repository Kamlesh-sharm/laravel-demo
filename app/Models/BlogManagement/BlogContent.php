<?php

namespace App\Models\BlogManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogContent extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'blog_category_id',
    'blog_id',
    'title',
    'slug',
    'content',
    'meta_keywords',
    'meta_description'
  ];

  public function blogCategory()
  {
    return $this->belongsTo('App\Models\BlogManagement\BlogCategory');
  }

  public function blog()
  {
    return $this->belongsTo('App\Models\BlogManagement\Blog');
  }

  public function blogContentLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
