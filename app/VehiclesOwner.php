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

    static public function hasJoin(int $vehicleId = 0, int $ownerId = 0) {
    	$vo = self::where('vehicle_id',$vehicleId)
  					->where('owner_id',$ownerId)
  					->first();
  		if ($vo instanceof VehiclesOwner) {
  			return $vo->id > 0;
  		}
  		return false;
    }

}
