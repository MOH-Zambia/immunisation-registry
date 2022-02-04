<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_uid')->unique();
            $table->string('card_number')->nullable();
            $table->string('NRC')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('drivers_license')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();
            $table->char('sex', 1);
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('occupation')->nullable();
            $table->boolean('status')->default(1); //Status (1=Active, 0=Inactive, e.g., in case of migration or death)
            $table->string('contact_number')->nullable();
            $table->string('contact_email_address')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_NRC')->nullable();
            $table->string('guardian_passport_number')->nullable();
            $table->string('guardian_contact_number')->nullable();
            $table->string('guardian_contact_email_address')->nullable();
            $table->string('next_of_kin_name')->nullable();
            $table->string('next_of_kin_contact_number')->nullable();
            $table->string('next_of_kin_contact_email_address')->nullable();
            $table->integer('nationality')->unsigned()->default(248); //country_id
            $table->integer('facility_id')->unsigned();
            $table->integer('record_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['client_uid', 'NRC', 'passport_number']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('facility_id')->references('id')->on('facilities');
            $table->foreign('record_id')->references('id')->on('records');
            $table->foreign('nationality')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function(Blueprint $table)
        {
            $table->dropForeign(['facility_id']);
            $table->dropForeign(['record_id']);
        });

        Schema::dropIfExists('clients');
    }
}
