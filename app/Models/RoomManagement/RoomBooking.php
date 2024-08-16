<?php

namespace App\Models\RoomManagement;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
  use HasFactory;

  protected $fillable = [
    'booking_number',
    'user_id',
    'vendor_id',
    'customer_name',
    'customer_email',
    'customer_phone',
    'room_id',
    'arrival_date',
    'departure_date',
    'guests',
    'subtotal',
    'discount',
    'grand_total',
    'currency_symbol',
    'currency_symbol_position',
    'currency_text',
    'currency_text_position',
    'payment_method',
    'gateway_type',
    'attachment',
    'invoice',
    'payment_status',
    'comission',
    'received_amount',
    'commission_percentage'
  ];

  public function hotelRoom()
  {
    return $this->belongsTo('App\Models\RoomManagement\Room', 'room_id', 'id');
  }

  public function roomBookedByUser()
  {
    return $this->belongsTo('App\Models\User', 'user_id', 'id');
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
