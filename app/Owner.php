<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Owner extends BaseModel
{
  
  public $timestamps = true;

  public function company() {
  	return $this->belongsTo('App\Company', 'company_id');
  }

  public function vehicles() {
  	return $this->belongsToMany('App\Vehicles', 'vehicles_owners','owner_id','vehicle_id');
  }

  static public function matchIdByName(string $name = ""):int {
		return self::matchIdByNameAndTable($name,"owners");
	}

}
