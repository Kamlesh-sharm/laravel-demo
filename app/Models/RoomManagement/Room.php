<?php

namespace App\Models\RoomManagement;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  use HasFactory;

  protected $fillable = [
    'vendor_id',
    'slider_imgs',
    'featured_img',
    'status',
    'max_guests',
    'bed',
    'bath',
    'rent',
    'latitude',
    'longitude',
    'address',
    'email',
    'phone',
    'is_featured',
    'avg_rating',
    'quantity'
  ];

  public function images()
  {
    return $this->hasMany(RoomImage::class);
  }

  public function roomContent()
  {
    return $this->hasMany('App\Models\RoomManagement\RoomContent');
  }
  public function room_content()
  {
    return $this->hasOne('App\Models\RoomManagement\RoomContent');
  }

  public function roomBooking()
  {
    return $this->hasMany('App\Models\RoomManagement\RoomBooking');
  }

  public function roomReview()
  {
    return $this->hasMany('App\Models\RoomManagement\RoomReview');
  }

  /**
   * scope a query to only those rooms whose status is show.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeStatus($query)
  {
    return $query->where('status', 1);
  }

  //vendor
  public function vendor()
  {
    return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
  }
  //user
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
