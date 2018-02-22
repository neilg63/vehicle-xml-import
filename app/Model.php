<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Model extends Model
{

	protected $fillable = [
		'name',
		'is_hgv',
		'weight_category',
		'no_wheels'
	];

    public function maker() {
    	return $this->belongsTo('App\Maker', 'maker_id');
    }
}
