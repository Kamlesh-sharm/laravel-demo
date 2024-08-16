<?php

namespace App\Models\RoomManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomAmenity extends Model
{
  use HasFactory;

  protected $fillable = ['language_id', 'name', 'serial_number'];

  public function amenityLang()
  {
    return $this->belongsTo('App\Models\Language');
  }
}
