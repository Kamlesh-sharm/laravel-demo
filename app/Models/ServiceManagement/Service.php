<?php

namespace App\Models\ServiceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
	use HasFactory;

	protected $fillable = [
		'service_icon',
		'details_page_status',
		'serial_number',
		'is_featured'
	];

	public function serviceContent()
	{
		return $this->hasMany('App\Models\ServiceManagement\ServiceContent');
	}
}
