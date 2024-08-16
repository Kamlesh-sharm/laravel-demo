<?php

namespace App\Models\RoomManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomReview extends Model
{
  use HasFactory;

  protected $fillable = ['user_id', 'vendor_id', 'room_id', 'rating', 'comment'];

  public function roomReviewedByUser()
  {
    return $this->belongsTo('App\Models\User', 'user_id', 'id');
  }

  public function reviewOfRoom()
  {
    return $this->belongsTo('App\Models\RoomManagement\Room');
  }
}
