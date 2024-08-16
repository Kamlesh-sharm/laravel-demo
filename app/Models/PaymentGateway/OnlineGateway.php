<?php

namespace App\Models\PaymentGateway;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineGateway extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'keyword',
    'information',
    'status'
  ];

  public $timestamps = false;
}
