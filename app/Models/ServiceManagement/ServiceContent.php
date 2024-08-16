<?php

namespace App\Models\ServiceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceContent extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'service_id',
    'title',
    'slug',
    'summary',
    'content',
    'meta_keywords',
    'meta_description'
  ];

  public function service()
  {
    return $this->belongsTo('App\Models\ServiceManagement\Service');
  }

  public function serviceContentLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
