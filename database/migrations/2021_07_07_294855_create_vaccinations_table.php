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
            $table->integer('client_id')->unsigned();
            $table->integer('vaccine_id')->unsigned();
            $table->integer('provider_id')->unsigned()->nullable(); //vaccinator
            $table->date('date'); //Complete date, without time, following ISO 8601
            $table->string('dose_number')->default('1'); //First, Second, Third, Fourth, Fifth, Booster
            $table->date('date_of_next_dose')->nullable();
            $table->string('type_of_strategy')->nullable(); //(intramural, extramural, etc.)
            $table->string('vaccine_batch_number')->nullable(); // BNT162b2
            $table->date('vaccine_batch_expiration_date')->nullable();
            $table->string('vaccinating_organization')->default('Ministry of Health');
            $table->integer('vaccinating_country_id')->unsigned()->default(248); //ZMB - ISO 3166 Country
            $table->integer('certificate_id')->unsigned()->nullable();
            $table->integer('facility_id')->unsigned();
            $table->string('event_uid')->nullable(); //DHIS2 event uid
            $table->integer('record_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['client_id']);
        });

        Schema::table('vaccinations', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('vaccine_id')->references('id')->on('vaccines');
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('certificate_id')->references('id')->on('certificates');
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('vaccinating_country_id')->references('id')->on('countries');
            $table->foreign('record_id')->references('id')->on('records');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vaccinations', function(Blueprint $table)
        {
            $table->dropForeign(['record_id']);
            $table->dropForeign(['vaccinating_country_id']);
            $table->dropForeign(['facility_id']);
            $table->dropForeign(['certificate_id']);
            $table->dropForeign(['provider_id']);
            $table->dropForeign(['vaccine_id']);
            $table->dropForeign(['client_id']);
        });

        Schema::dropIfExists('vaccinations');
    }
}
