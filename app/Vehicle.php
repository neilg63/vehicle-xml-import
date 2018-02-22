<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'reg',
        'type',
        'usage',
        'transmission',
        'colour'
    ];

    public function model() {
    	return $this->belongsTo('App\Vehicle', 'model_id');
    }

    public function owners() {
    	return $this->belongsToMany('App\Owner', 'vehicles_owners','vehicle_id','owner_id');
    }                  
                                                                     
}
