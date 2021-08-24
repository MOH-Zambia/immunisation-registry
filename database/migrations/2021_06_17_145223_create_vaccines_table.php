<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name');
            $table->string('short_description');
            $table->string('vaccine_code');
            $table->string('vaccine_manufacturer'); // e.g Pfizer - Human readable name of vaccine manufacturer 
            $table->string('vaccine_type'); // e.g Covid19 - Human readable name of vaccine
            $table->string('commercial_formulation'); //(e.g., hexavalent, pentavalent) 
            $table->string('vaccine_status'); //Active 0r Inactive
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('vaccines');
    }
}
