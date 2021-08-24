<?php

use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Grimzy\LaravelMysqlSpatial\Schema\Blueprint;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('province_id');
            $table->string('name');
            $table->string('district_type')->nullable();
            $table->integer('population')->nullable();
            $table->float('pop_density')->nullable();
            $table->float('area_sq_km')->nullable();
            // $table->polygon('geometry', 4326);
            $table->polygon('geometry', 3857);
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('province_id')->references('id')->on('provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
