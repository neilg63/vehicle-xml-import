<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Vmodel extends BaseModel
{

	public $timestamps = true;

	protected $table = 'vmodels';

	protected $fillable = [
		'name',
		'is_hgv',
		'weight_category',
		'no_wheels'
	];

	protected $casts = [
      'is_hgv' => 'boolean'
  ];

  public function maker() {
  	return $this->belongsTo('App\Maker');
  }

  static public function matchIdByNameAndMake(string $name = "", $makerId = 0):int {
		return self::matchIdByNameWithFKAndTable($name, $makerId, "vmodels", "name", "maker_id");
	}

}
