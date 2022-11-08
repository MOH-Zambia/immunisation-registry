<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // \Laravelista\LumenVendorPublish\VendorPublishCommand::class,
        Commands\ImportDHIS2Data::class,
        Commands\GenerateVaccinationCertificates::class,
        Commands\ImportDHIS2DataPerFacility::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $startDate = "2020-01-01";
        $endDate = date('Y-m-d');
        $facilityDhis2Uid = "HvCdWhbVEvI"; //Chilenje First Level Hospital - Default Facility Picked
        foreach (['06:00', '11:00', '14:00'] as $cert_gen_time) {
            $schedule->command("command:GenerateVaccinationCertificates")->daily()->at($cert_gen_time); //->dailyAt($time);
        }
        // $schedule->command("command:ImportDHIS2DataPerFacility $startDate $endDate $facilityDhis2Uid")->daily()->at("12:00");
        $schedule->command("command:ImportUpdatedDHIS2Data")->daily()->at("01:30");
        $schedule->command("command:ImportUpdatedDHIS2ClientData")->daily()->at("22:00");
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

}
