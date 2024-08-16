<?php

namespace App\Models\PaymentGateway;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineGateway extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'short_description',
    'instructions',
    'status',
    'attachment_status',
    'serial_number'
  ];

  public $timestamps = false;
}
