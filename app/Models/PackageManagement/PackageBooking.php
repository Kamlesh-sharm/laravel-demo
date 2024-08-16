<?php

namespace App\Models\PackageManagement;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageBooking extends Model
{
  use HasFactory;

  protected $fillable = [
    'booking_number',
    'user_id',
    'vendor_id',
    'customer_name',
    'customer_email',
    'customer_phone',
    'package_id',
    'visitors',
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

  public function tourPackage()
  {
    return $this->belongsTo('App\Models\PackageManagement\Package', 'package_id', 'id');
  }

  public function packageBookedByUser()
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
