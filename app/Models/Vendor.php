<?php

namespace App\Models;

use App\Models\PackageManagement\Package;
use App\Models\RoomManagement\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Vendor extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $fillable = [
        'photo',
        'email',
        'phone',
        'username',
        'password',
        'status',
        'amount',
        'email_verified_at',
        'avg_rating',
        'show_email_addresss',
        'show_phone_number',
        'show_contact_form',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'vendor_id', 'id');
    }
    public function packages()
    {
        return $this->hasMany(Package::class, 'vendor_id', 'id');
    }
    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }
    public function support_ticket()
    {
        return $this->hasMany(SupportTicket::class);
    }
}
