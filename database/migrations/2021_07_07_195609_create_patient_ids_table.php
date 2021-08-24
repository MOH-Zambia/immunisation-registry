<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_ids', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id')->nullable(); //country of issue
            $table->date('expiration_date')->nullable();
            $table->string('id_num');
            $table->integer('id_type_id');
            $table->date('issue_date')->nullable();
            $table->string('issue_place')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('id_type_id')->references('id')->on('id_types');
            // $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_ids');
    }
}
