<?php

namespace App\Models\BasicSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
  use HasFactory;

  protected $fillable = ['icon', 'url', 'serial_number'];
}
