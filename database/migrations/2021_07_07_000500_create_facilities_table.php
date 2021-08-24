<?php

use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Grimzy\LaravelMysqlSpatial\Schema\Blueprint;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('facility_id')->nullable();
            $table->string('HMIS_code')->nullable();
            $table->string('DHIS2_UID')->nullable();
            $table->string('smartcare_GUID')->nullable();
            $table->string('eLMIS_ID')->nullable();
            $table->string('iHRIS_ID')->nullable();
            $table->integer('district_id');
            $table->string('name');
            $table->string('facility_type');
            $table->string('ownership'); //Sector (public, private, or other)
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->integer('catchment_population_head_count')->nullable();
            $table->integer('catchment_population_cso')->nullable();
            $table->string('operation_status')->nullable();
            $table->point('location')->nullable();
            $table->string('location_type')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facilities');
    }
}
