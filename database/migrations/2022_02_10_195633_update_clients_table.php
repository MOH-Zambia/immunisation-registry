<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->timestamp('source_updated_at', 0)->nullable()->after('record_id');
            $table->timestamp('source_created_at', 0)->nullable()->after('record_id');
            $table->renameColumn('client_uid', 'source_id');

            $table->index(['source_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['source_id']);

            $table->renameColumn('source_id', 'client_uid');
            $table->dropColumn('source_created_at');
            $table->dropColumn('source_updated_at');
        });
    }
}
