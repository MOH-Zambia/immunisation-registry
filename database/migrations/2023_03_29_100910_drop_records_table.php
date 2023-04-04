<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('records', function(Blueprint $table)
        {
            // $table->dropIndex(['hash']);
        });

        Schema::dropIfExists('records');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('record_id'); //Record identifier from data source
            $table->string('data_source');
            $table->string('data_type');
            $table->string("hash");
            $table->json('data');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['hash']);
        });
    }
}
