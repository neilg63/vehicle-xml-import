<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Maker extends BaseModel
{
  protected $fillable = [
		'name'
	];

	public $timestamps = true;

	static public function matchIdByName(string $name = "") { 
		return self::matchIdByNameAndTable($name,"makers");
	}

}
