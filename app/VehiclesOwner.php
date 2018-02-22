<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehiclesOwner extends Model
{
    protected $table = 'vehicles_owners';

    public $timestamps = true;

    public function owner() {
    	return $this->belongsTo('App\Owner', 'owner_id');
    }

    public function vehicle() {
    	return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }

}
