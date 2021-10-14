<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVaccinationsByManufacturer extends Migration
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
            'CREATE OR REPLACE VIEW `view_vaccinations_by_manufacturer` AS
                SELECT
                    vaccines.vaccine_manufacturer, COUNT(vaccinations.id) AS number_of_doses
                FROM vaccinations
                    LEFT JOIN vaccines ON vaccines.id = vaccinations.vaccine_id
                GROUP BY vaccines.vaccine_manufacturer';
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView(): string
    {
        return
            'DROP VIEW IF EXISTS `view_vaccinations_by_manufacturer`';
    }
}
