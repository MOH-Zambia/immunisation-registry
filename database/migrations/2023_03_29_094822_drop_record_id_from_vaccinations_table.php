<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRecordIdFromVaccinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaccinations', function (Blueprint $table) {

            $table->dropForeign(['record_id']);
            
            //If the record_id column exists on vaccinations table
            if (Schema::hasColumn('vaccinations', 'record_id')){
                $table->dropColumn('record_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vaccinations', function (Blueprint $table) {
            $table->integer('record_id')->unsigned();
        });
    }
}
