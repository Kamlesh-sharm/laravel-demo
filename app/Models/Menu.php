<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'language_id',
        'menus',
    ];
    public function language()
    {
        return $this->belongsTo('App\Language');
    }
}
