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
            $table->string('client_id')->unique();
            $table->string('card_number')->nullable();
            $table->string('NRC')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();
            $table->char('sex');
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('occupation')->nullable();
            $table->string('status')->default('Active'); //Status (active/inactive, e.g., in case of migration or death)
            $table->string('contact_number')->nullable();
            $table->string('contact_email_address')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('next_of_kin_name')->nullable();
            $table->string('next_of_kin_contact_number')->nullable();
            $table->string('next_of_kin_contact_email_address')->nullable();
            $table->integer('facility_id')->unsigned();
            $table->integer('record_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('facility_id')->references('id')->on('facilities');
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
        Schema::table('clients', function(Blueprint $table)
        {
            $table->dropForeign(['facility_id', 'record_id']);
        });

        Schema::dropIfExists('clients');
    }
}
