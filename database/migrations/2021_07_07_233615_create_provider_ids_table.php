<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_ids', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id');
            $table->date('expiration_date');
            $table->string('id_num');
            $table->integer('id_type_id');
            $table->date('issue_date');
            $table->string('issue_place');
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('id_type_id')->constrained('id_types')->nullable();
            // $table->foreign('country_id')->constrained('countries')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_ids');
    }
}
