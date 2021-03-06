<?php

namespace App\Http\Services;

use App\Maker;
use App\Vmodel;
use App\Vehicle;
use App\Company;
use App\Owner;
use App\VehiclesOwner;
use Illuminate\Support\Facades\DB;

class VehicleRecorder {

  static public function saveFromFlatData(array $item = array()) {
    	$isNew = false;
    	$valid = false;
    	// must have a license_plate
    	// unique identifier for vehicle instances
    	if (isset($item['license_plate'])) {
    		$item['id'] = Vehicle::matchByReg($item['license_plate']);
    		$item['reg'] = $item['license_plate'];
    		$item['new'] = $item['id'] < 1;
    		$isValid = true;
    	}
    	if ($isValid) {
    		$item += [
    			'maker_id' => 0,
    		 	'vmodel_id' => 0,
    			'owner_id' => 0,
    			'company_id' => 0
    		];
    		
    		self::matchMaker($item);
    		
    		self::matchModel($item);
    		
    		self::matchCompany($item);
    		
    		self::matchOwner($item);
    		
    		if (
    			$item['vmodel_id'] > 0  
    			&& $item['owner_id'] > 0
    		) {
    			self::saveVehicleData($item);
    		}
    	}
    	return $item;
    }

  static private function matchMaker(array &$item) {
  	if (isset($item['manufacturer'])) {
			$item['maker_id'] = Maker::matchIdByName($item['manufacturer']);
		}

		if ($item['maker_id'] < 1) {
			$maker = new Maker;
			$maker->name = trim($item['manufacturer']);
			$maker->save();
			$item['maker_id'] = $maker->id;
		}
  }

  static private function matchModel(array &$item) {
  	if ($item['maker_id'] > 0) {
			if (isset($item['model'])) {
  			$model = Vmodel::matchByNameAndMake($item['model'],$item['maker_id']);
  			if ($model instanceof Vmodel) {
  				$item['vmodel_id'] = $model->id;
  			} else {
  				$model = new Vmodel;
  			}	
				$model->maker_id = $item['maker_id'];
				$model->name = $item['model'];
				if (isset($item['is_hgv'])) {
					$model->is_hgv = (bool) $item['is_hgv'];
				}
				if (isset($item['no_wheels'])) {
					$model->no_wheels = (int) $item['no_wheels'];
				}
				if (isset($item['weight_category'])) {
					$model->weight_category = (int) $item['weight_category'];
				}
				$model->save();
				$item['vmodel_id'] = $model->id;
  		}
		}
  }

  static private function matchCompany(array &$item) {
  	if (isset($item['owner_company'])) {
			$item['company_id'] = Company::matchIdByName($item['owner_company']);
			if ($item['company_id'] < 1) {
				$company = new Company;
				$company->name = $item['owner_company'];
				$company->save();
				$item['company_id'] = $company->id;
			}
		}
  }

  static private function matchOwner(array &$item) {
  	if (isset($item['owner_name']) && $item['company_id'] > 0) {
			$owner = Owner::matchByName($item['owner_name']);
			if ($owner instanceof Owner) {
				$item['owner_id'] = $owner->id;
			} else {
				$owner = new Owner;
			}
			$owner->company_id = $item['company_id'];
			$owner->name = $item['owner_name'];
			$owner->profession = $item['owner_profession'];
			$owner->save();
			$item['owner_id'] = $owner->id;
		}
  }

  static private function saveVehicleData(array &$item) {
		if ($item['id'] > 0) {
			$vehicle = Vehicle::find($item['id']);
		} else {
			$vehicle = new Vehicle;
		}
		foreach ($item as $key => $value) {
			switch ($key) {
				case 'vmodel_id':
				case 'no_doors':
				case 'no_seats':
				case 'engine_cc':
					$vehicle->{$key} = (int) $value;
					break;
				case 'has_gps':
				case 'has_trailer':
				case 'has_boot':
					$vehicle->{$key} = (bool) $value;
					break;
				case 'sunroof':
					$vehicle->has_sunroof = (bool) $value;
					break;
				case 'type':
				case 'fuel_type':
				case 'transmission':
				case 'usage':
					$vehicle->{$key} = strtolower(trim($value));
					break;
				case 'colour':
					$vehicle->{$key} = trim($value);
					break;
				case 'reg':
					$vehicle->{$key} = strtoupper(trim($value));
					break;
			}
		}
		$vehicle->save();
		$item['id'] = $vehicle->id;
		$hasJoin = VehiclesOwner::hasJoin($item['id'], $item['owner_id']);
		if (!$hasJoin) {
			$vo = new VehiclesOwner;
  		$vo->owner_id = $item['owner_id'];
  		$vo->vehicle_id = $item['id'];
  		$vo->save();
		}
  }

}