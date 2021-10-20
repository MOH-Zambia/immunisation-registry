<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVaccinationsByMonth extends Migration
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
            "CREATE OR REPLACE VIEW `view_vaccinations_by_months` AS
                SELECT
                    DATE_FORMAT(date, '%m-%Y'),
                    COUNT(id) AS number_of_doses
                FROM vaccinations
                GROUP BY DATE_FORMAT(date, '%m-%Y')
                ORDER BY DATE_FORMAT(date, '%m-%Y')";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView(): string
    {
        return
            'DROP VIEW IF EXISTS `view_vaccinations_by_month`';
    }
}
