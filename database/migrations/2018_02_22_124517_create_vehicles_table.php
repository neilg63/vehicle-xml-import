<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vmodel_id');
            $table->string('reg')->unique();
            $table->string('type');
            $table->string('fuel_type');
            $table->string('usage');
            $table->string('colour');
            $table->string('transmission');
            $table->integer('engine_cc');
            $table->tinyInteger('no_doors', 3);
            $table->tinyInteger('no_seats', 3);
            $table->tinyInteger('has_gps',1);
            $table->tinyInteger('has_sunroof',1);
            $table->tinyInteger('has_trailer',1);
            $table->tinyInteger('has_boot',1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
