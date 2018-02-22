<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends BaseModel
{

	protected $table = 'companies';

  protected $fillable = [
		'name'
	];

	public $timestamps = true;

	static public function matchIdByName(string $name = ""):int {
		return self::matchIdByNameAndTable($name,"companies");
	}

}
