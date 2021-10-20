<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVaccinationsPast12Months extends Migration
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
            'CREATE OR REPLACE VIEW `view_vaccinations_past_12_months` AS
                SELECT dates.date, COUNT(vaccinations.vaccine_id) as number_of_doses
                FROM (
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 12 MONTH AS date UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 11 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 10 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 9 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 8 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 7 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 6 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 5 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 4 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 3 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 2 MONTH UNION ALL
                    SELECT LAST_DAY(CURRENT_DATE) + INTERVAL 1 DAY - INTERVAL 1 MONTH
                ) AS dates
                LEFT JOIN vaccinations ON vaccinations.date >= dates.date AND vaccinations.date < dates.date + INTERVAL 1 MONTH
                GROUP BY dates.date';
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    private function dropView(): string
    {
        return
            'DROP VIEW IF EXISTS `view_vaccinations_past_12_months`';
    }
}
