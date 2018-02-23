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

   protected $casts = [
      'has_gps' => 'boolean',
      'has_boot' => 'boolean',
      'has_sunroof' => 'boolean',
      'has_trailer' => 'boolean',
  ];

  public $timestamps = true;

  public function vmodel() {
  	return $this->belongsTo('App\Vmodel');
  }

  public function owners() {
  	return $this->belongsToMany('App\Owner', 'vehicles_owners','vehicle_id','owner_id');
  }

  static public function allData():array {
    $vehicles = self::all();
		$items = array();
		foreach ($vehicles as $vehicle) {
			$item = $vehicle->toArray();
			$vmodel = $vehicle->vmodel()
				->select([
					"id",
					"maker_id",
					"name",
					"is_hgv",
					"no_wheels",
					"weight_category"
				])
				->first();
			$item['model'] = (object) $vmodel->toArray();
			$maker = $vmodel->maker()->select(["id","name"])->first();
			$item['maker'] = (object) $maker->toArray();
			$owners = $vehicle->owners()->get();
			$item['owners'] = array();
			foreach ($owners as $owner) {
				$record = $owner->toArray();
				$company = $owner->company()->select(["id","name"])->first();
				$record['company'] = $company->toArray();
				$item['owners'][] = (object) $record;
			}
			
			$items[] = (object) $item;
		}
		return $items;
  }

  static public function matchByReg(string $reg = ""):int {
  	return self::matchIdByNameAndTable($reg,"vehicles", "reg", true);
  }               
                                                                     
}
