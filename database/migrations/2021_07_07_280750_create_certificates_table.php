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
            $table->uuid('certificate_uuid')->unique(); //e.g 01/ZM/1532F4ED22C0BE2BF30540947C93D322#74D0CC9A
            $table->integer('client_id')->unsigned()->unique();
            $table->string('trusted_vaccine_code')->nullable(); //Africa CDC Trusted Vaccine Code
            $table->string('target_disease');
            $table->date('certificate_expiration_date')->nullable(); //2030-09-19 - Complete date, without time, following ISO 8601
            $table->string('certificate_status')->default('Valid'); //e.g Valid, Invalid, Revoked
            $table->string('signature_algorithm')->nullable()->default('RS256'); //1 stands for RS256 (in the future you can declare ne)
            $table->integer('certificate_issuing_authority_id')->nullable(); //Indicates type of certificate (1=GreenPass,2=Vaccination,3=Recovery)
            $table->string('vaccination_certificate_batch_number')->nullable(); //e.g 123456789 - Coarse issuing batch number for the certificate (can be used for revocation), not to be confused with vaccine batch number
            $table->binary('qr_code');
            $table->string('qr_code_path');
            $table->string('certificate_url');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificates', function(Blueprint $table)
        {
            $table->dropForeign(['client_id']);
        });

        Schema::dropIfExists('certificates');
    }
}
