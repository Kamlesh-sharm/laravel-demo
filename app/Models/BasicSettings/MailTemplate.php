<?php

namespace App\Models\BasicSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
  use HasFactory;

  protected $fillable = ['mail_subject', 'mail_body'];

  public $timestamps = false;

  // accessor
  public function getMailTypeAttribute($value)
  {
    return str_replace('_', ' ', $value);
  }
}
