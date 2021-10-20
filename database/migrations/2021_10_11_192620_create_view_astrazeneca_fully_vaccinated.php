<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewAstrazenecaFullyVaccinated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement($this->dropView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function createView(): string
    {
        return
            "CREATE OR REPLACE VIEW `view_astrazeneca_fully_vaccinated` AS
                SELECT
                    client_id
                FROM vaccinations
                WHERE vaccine_id = 1 AND dose_number = 1 AND client_id IN (
                    SELECT
                        client_id
                    FROM vaccinations
                    WHERE vaccine_id = 1 AND dose_number = 2)";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView(): string
    {
        return
            'DROP VIEW IF EXISTS `view_astrazeneca_fully_vaccinated`';
    }
}
