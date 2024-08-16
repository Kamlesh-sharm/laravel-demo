<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function page() {
        return $this->belongsTo('App\Models\Page');
    }

    public function language() {
        return $this->belongsTo('App\Models\Language');
    }
}
