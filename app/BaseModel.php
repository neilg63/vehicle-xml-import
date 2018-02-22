<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model {

	static protected function matchIdByNameAndTable(string $name = "", string $tableName = "", $targetField = "name", $alphanumOnly = false):int {
		return self::matchIdByNameWithFKAndTable($name, 0, $tableName, $targetField, "", $alphanumOnly);
	}

	static protected function matchIdByNameWithFKAndTable(string $name = "", $foreignKey = 0, string $tableName = "", $targetField = "name", $fkName = "", $alphanumOnly = false):int {
		$id = 0;
		if (!empty($name)) {
			$name = trim($name);
			$mode = 'LIKE';
			if ($alphanumOnly) {
				$name = strtoupper($name);
				// build regex string to ignore non-alphanum chrarcters. NB REGEXP is MySQL only
				$name = preg_replace('#[^a-z0-9]#i', '.*', $name);
				$mode = 'REGEXP';
			} else {
				$name = strtolower($name);
			}
			$query = DB::table($tableName)
				->select($tableName . ".id")
				->where($tableName . "." . $targetField, $mode, $name);
				if (!empty($fkName) && $foreignKey > 0) {
					$query->where($fkName, $foreignKey);
				}
			$result = $query->get();
			$data = $result->toArray();
			
			if (!empty($data) && array_key_exists(0,$data)) {
				$id = $data[0]->id;
			}
		}
		return $id;
	}

}