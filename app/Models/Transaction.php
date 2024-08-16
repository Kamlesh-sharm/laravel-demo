<?php

namespace App\Models;

use App\Models\PackageManagement\PackageBooking;
use App\Models\RoomManagement\RoomBooking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transcation_id',
        'booking_id',
        'transcation_type',
        'user_id',
        'vendor_id',
        'payment_status',
        'payment_method',
        'grand_total',
        'commission',
        'gateway_type',
        'currency_symbol',
        'currency_symbol_position',
        'pre_balance',
        'after_balance',
    ];

    public function method()
    {
        return $this->belongsTo(WithdrawPaymentMethod::class, 'payment_method', 'id');
    }

    //room_booking 
    public function room_booking()
    {
        return $this->belongsTo(RoomBooking::class, 'booking_id', 'id');
    }
    //package_booking 
    public function package_booking()
    {
        return $this->belongsTo(PackageBooking::class, 'booking_id', 'id');
    }
    //vendor_id
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
}
