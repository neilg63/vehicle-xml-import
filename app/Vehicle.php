<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends BaseModel
{
    protected $fillable = [
        'reg',
        'type',
        'usage',
        'transmission',
        'colour'
    ];

    public $timestamps = true;

    public function vmodel() {
    	return $this->belongsTo('App\Vehicle', 'vmodel_id');
    }

    public function owners() {
    	return $this->belongsToMany('App\Owner', 'vehicles_owners','vehicle_id','owner_id');
    }

    static public function matchByReg(string $reg = ""):int {
    	return self::matchIdByNameAndTable($reg,"vehicles", "reg", true);
    }               
                                                                     
}
