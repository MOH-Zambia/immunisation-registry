<?php

namespace App\Console\Commands;


use App\Models\Vaccination;
use Symfony\Component\Console\Output\ConsoleOutput;

class PersistVaccination
{
    /** @var  ConsoleOutput */
    protected static $output;

    /**
     * Create a new PersistVaccination instance.
     *
     * @return void
     */
    public function __construct()
    {
        self::$output = new ConsoleOutput();
    }

    public function shouldUpdate($_vaccination, $_client_id, $_created_diff, $_updated_diff): bool
    {

        $_abs_created_diff = abs($_created_diff);
        $_abs_updated_diff = abs($_updated_diff);
        if (empty($_vaccination->source_created_at) || empty($_vaccination->source_updated_at)) {
            return true;
        } else if (($_updated_diff >= 2) && ($_created_diff <= 2) && ($_created_diff >= -2) && ($_vaccination->client_id == $_client_id)) {
            return true; 
        } else if ((($_abs_created_diff >= 7198) && ($_abs_created_diff <=7202)) || 
                  (($_abs_updated_diff >= 7198) && ($_abs_updated_diff <=7202))) { //This block is to cater for 2 hours timezone differences between the two servers
            return true;
        }
        return false;
    }

    public function assignCommonVaccinationFields($_vaccination, $_event, $_facility_id): ? Vaccination
    {
        $_vaccination->date = $_event['eventDate'];

        switch ($_event['programStage']) {
            case 'a1jCssI2LkW': //programStage: Vaccination Dose 1
                $_vaccination->dose_number = '1';
                break;
            case 'RiV7VDxXQLN': //programStage: Vaccination Dose 2
                $_vaccination->dose_number = '2';
                break;
            case 'jatC7jRwVKO': //programStage: Vaccination Booster Dose
                $_vaccination->dose_number = 'Booster';
                break;
        }

        foreach ($_event['dataValues'] as $dataValue) {
            if ($dataValue['dataElement'] == 'bbnyNYD1wgS') { //Vaccine Name
                switch ($dataValue['value']) {
                    case 'AstraZeneca_zm':
                        $_vaccination->vaccine_id = 1;
                        break;
                    case 'Johnson_Johnsons_zm':
                        $_vaccination->vaccine_id = 3;
                        break;
                    case 'Sinopharm':
                        $_vaccination->vaccine_id = 7;
                        break;
                    case 'Pfizer':
                        $_vaccination->vaccine_id = 6;
                        break;
                    case 'Moderna':
                        $_vaccination->vaccine_id = 4;
                        break;
                }
            } else if ($dataValue['dataElement'] == 'Yp1F4txx8tm') { //Batch Number
                $_vaccination->vaccine_batch_number = $dataValue['value'];
            } else if ($dataValue['dataElement'] == 'FFWcps4MfuH') { //Suggested date for next dose
                $_vaccination->date_of_next_dose = $dataValue['value'];
            }
        }

        $_vaccination->facility_id = $_facility_id;
        $_vaccination->source_created_at = $_event['created'];
        $_vaccination->source_updated_at = $_event['lastUpdated'];

        return $_vaccination;
    }

    public function saveVaccination($_event, $_client_id, $_facility_id): ? Vaccination
    {
        $_vaccination = new Vaccination();

        $_vaccination->client_id = $_client_id;
        $_vaccination->source_id = $_event['event'];

        $_vaccination = self::assignCommonVaccinationFields($_vaccination, $_event, $_facility_id);

        $_vaccination->save();

        $_time = date('Y-m-d H:i:s');
        self::$output->writeln("{$_time} <info>SAVED Vaccination, ID Number:</info> {$_vaccination->id}, <info>UID :</info> {$_vaccination->source_id}, <info>Client ID:</info> {$_vaccination->client_id}, <info>Dose:</info> {$_vaccination->dose_number}, <info>Event Date:</info> {$_vaccination->date}, <info>Facility:</info> {$_vaccination->facility_id}");

        return $_vaccination;
    }

    public function updateVaccination($_vaccination, $_event, $_facility_id): ? Vaccination
    {
        $_vaccination = self::assignCommonVaccinationFields($_vaccination, $_event, $_facility_id);

        $_vaccination->update();

        $_time = date('Y-m-d H:i:s');
        self::$output->writeln("{$_time} <info>UPDATED Vaccination, ID Number:</info> {$_vaccination->id}, <info>UID :</info> {$_vaccination->source_id}, <info>Client ID:</info> {$_vaccination->client_id}, <info>Dose:</info> {$_vaccination->dose_number}, <info>Event Date:</info> {$_vaccination->date}, <info>Facility:</info> {$_vaccination->facility_id}");

        return $_vaccination;
    }
}