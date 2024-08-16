<?php

namespace App\Models\RoomManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
  use HasFactory;

  protected $fillable = [
    'language_id',
    'name',
    'status',
    'serial_number'
  ];

  public function roomCategoryLang()
  {
    return $this->belongsTo('App\Models\Language');
  }

  public function roomContentList()
  {
    return $this->hasMany('App\Models\RoomManagement\RoomContent');
  }
}
