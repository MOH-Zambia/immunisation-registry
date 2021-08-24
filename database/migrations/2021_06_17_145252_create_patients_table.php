<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('patient_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names');
            $table->char('sex');
            $table->string('occupation');
            $table->string('status'); //Status (active/inactive, e.g., in case of migration or death)
            $table->string('contact_number')->nullable();
            $table->string('contact_email_address')->nullable();
            $table->string('next_of_kin_name')->nullable();
            $table->string('next_of_kin_contact_number')->nullable();
            $table->string('next_of_kin_contact_email_address')->nullable();
            $table->date('date_of_birh')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('residence')->nullable();
            $table->integer('record_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
