<?php

namespace App\Models\RoomManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomContent extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'room_category_id',
    'room_id',
    'title',
    'slug',
    'summary',
    'description',
    'amenities',
    'meta_keywords',
    'meta_description'
  ];

  public function roomCategory()
  {
    return $this->belongsTo('App\Models\RoomManagement\RoomCategory', 'room_category_id', 'id');
  }

  public function room()
  {
    return $this->belongsTo('App\Models\RoomManagement\Room');
  }

  public function roomContentLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
