<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
  public function model() {
  	return $this->belongsTo('App\Company', 'company_id');
  }
}
