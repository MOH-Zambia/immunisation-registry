<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('vaccine_id');
            $table->integer('provider_id'); //vaccinator
            $table->date('date'); //Complete date, without time, following ISO 8601
            $table->string('type_of_strategy'); //y (intramural, extramural, etc.)
            $table->string('vaccine_batch_number'); // BNT162b2
            $table->date('vaccine_batch_expiration_date')->nullable();
            $table->string('vaccinating_organization_id');
            $table->string('vaccinating_country'); //ZMB - ISO 3166 Country
            $table->integer('record_id');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('record_id')->references('id')->on('records');
            // $table->foreign('patient_id')->references('id')->on('patients');
            // $table->foreign('vaccine_id')->references('id')->on('vaccines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaccinations');
    }
}
