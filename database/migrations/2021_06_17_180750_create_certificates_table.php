<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('vaccination_certificate_id')->unique(); //01/ZM/1532F4ED22C0BE2BF30540947C93D322#74D0CC9A
            $table->string('signature_algorithm')->default(1); //1 stands for RS256 (in the future you can declare ne)
            $table->integer('certificate_issuing_authority_id')->default(1); //Indicates type of certificate (1=GreenPass,2=Vaccination,3=Recovery)
            $table->string('vaccination_certificate_batch_number')->nullable(); //e.g 123456789 - Coarse issuing batch number for the certificate (can be used for revocation), not to be confused with vaccine batch number
            $table->integer('patient_id');
            $table->date('certificate_expiration_date'); //2030-09-19 - Complete date, without time, following ISO 8601
            $table->date('innoculated_since_date')->nullable();
            $table->date('recovery_date')->nullable();
            $table->string('patient_status')->nullable(); //e.g Vaccinated, 
            $table->integer('dose_1_id');
            $table->integer('dose_2_id')->nullable();
            $table->integer('dose_3_id')->nullable();
            $table->integer('dose_4_id')->nullable();
            $table->binary('qr_code');
            $table->string('certificate_url');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificates');
    }
}
