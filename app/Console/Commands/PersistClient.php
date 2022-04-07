<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use Symfony\Component\Console\Output\ConsoleOutput;

class PersistClient
{

    /** @var  ConsoleOutput */
    protected static $output;

    /**
     * Create a new PersistClient instance.
     *
     * @return void
     */
    public function __construct()
    {
        self::$output = new ConsoleOutput();
    }

    public function shouldUpdate($_client, $_tracked_entity_uid, $_created_diff, $_updated_diff): bool
    {
        $_abs_created_diff = abs($_created_diff);
        $_abs_updated_diff = abs($_updated_diff);
        if (empty($_client->source_created_at) || empty($_client->source_updated_at)) {
            return true;
        } else if (($_updated_diff >= 2) && 
                  ((($created_at_timestamps_difference <= 2) && ($created_at_timestamps_difference >= -2)) || 
                  ($_tracked_entity_uid == $_client->source_id))) {
            return true; 
        } else if ((($_abs_created_diff >= 7198) && ($_abs_created_diff <=7202)) || 
                  (($_abs_updated_diff >= 7198) && ($_abs_updated_diff <=7202))) { //This block is to cater for 2 hours timezone differences betweent the two servers
            return true;
        }
        return false;
    }

    public function assignCommonClientFields($_client, $_tracked_entity_instance, $_facility_id): ? Client
    {
        foreach ($_tracked_entity_instance['attributes'] as $attribute) {
            if ($attribute['attribute'] == 'zUQCBnWbBer') //Tracked Entity Attribute UID for Card Number
                $_client->card_number = $attribute['value'];
            else if ($attribute['attribute'] == 'Ewi7FUfcHAD') //Tracked Entity Attribute UID for NRC
                $_client->NRC = $attribute['value'];
            else if ($attribute['attribute'] == 'pd02AeZHXWi') //Tracked Entity Attribute UID for Passport Number
                $_client->passport_number = $attribute['value'];
            else if ($attribute['attribute'] == 'TfdH5KvFmMy') //Tracked Entity Attribute UID for First Name
                $_client->first_name = ucfirst(strtolower(trim($attribute['value'])));
            else if ($attribute['attribute'] == 'aW66s2QSosT') //Tracked Entity Attribute UID for Surname
                $_client->last_name = ucfirst(strtolower(trim($attribute['value'])));
            else if ($attribute['attribute'] == 'Bag3HrPOKRm') //Tracked Entity Attribute UID for Other Names
                $_client->other_names = ucfirst(strtolower(trim($attribute['value'])));
            else if ($attribute['attribute'] == 'CklPZdOd6H1')  //Tracked Entity Attribute UID for Sex
                $_client->sex = $attribute['value'][0];
            else if ($attribute['attribute'] == 'mAWcalQYYyk')  //Tracked Entity Attribute UID for DOB
                $_client->date_of_birth = $attribute['value'];
            else if ($attribute['attribute'] == 'ciCR6BBvIT4')  //Tracked Entity Attribute UID for Mobile phone number
                $_client->contact_number = $attribute['value'];
            else if ($attribute['attribute'] == 'ctpwSFedWFn')  //Tracked Entity Attribute UID for Email Address
                $_client->contact_email_address = $attribute['value'];
            else if ($attribute['attribute'] == 'VCtm2pySeEV')  //Tracked Entity Attribute UID for Address (current)
                $_client->address_line1 = $attribute['value'];
            else if ($attribute['attribute'] == 'gB3BrfkEmkC') //Tracked Entity Attribute UID for Guardian's NRC
                $_client->guardian_NRC = $attribute['value'];
            else if ($attribute['attribute'] == 'TodvbRCs4La') //Tracked Entity Attribute UID for Guardian's Passport Number
                $_client->guardian_passport_number = $attribute['value'];
            else if ($attribute['attribute'] == 'LY2bDXpNvS7')  //Tracked Entity Attribute UID for Occupation
                $_client->occupation = $attribute['value'];
        }
        $_client->facility_id = $_facility_id;
        $_client->source_created_at = $_tracked_entity_instance['created'];
        $_client->source_updated_at = $_tracked_entity_instance['lastUpdated'];

        return $_client;
    }

    public function saveClient($_tracked_entity_instance, $_facility_id, $_record_id): ? Client
    {
        $_client = new Client();
        $_client->source_id = $_tracked_entity_instance['trackedEntityInstance'];
        $_client->record_id = $_record_id;

        $_client = self::assignCommonClientFields($_client, $_tracked_entity_instance, $_facility_id);

        $_client->save(); //Save new client

        $_time = date('Y-m-d H:i:s');
        self::$output->writeln("{$_time} <info>SAVED Client ID Number:</info> {$_client->id} <info>UID:</info> {$_client->source_id} <info>First Name:</info> {$_client->first_name} <info>Surname:</info> {$_client->last_name} <info>DOB:</info> {$_client->date_of_birth} <info>Sex:</info> {$_client->sex} <info>Created At:</info> {$_client->source_created_at} <info>Facility:</info> {$_client->facility_id}");

        return $_client;
    }

    public function updateClient($_client, $_tracked_entity_instance, $_facility_id): ? Client
    {
        $_client = self::assignCommonClientFields($_client, $_tracked_entity_instance, $_facility_id);

        $_client->update(); //Update client info

        $_time = date('Y-m-d H:i:s');
        self::$output->writeln("{$_time} <info>UPDATED Client ID Number:</info> {$_client->id} <info>UID:</info> {$_client->source_id} <info>First Name:</info> {$_client->first_name} <info>Surname:</info> {$_client->last_name} <info>DOB:</info> {$_client->date_of_birth} <info>Sex:</info> {$_client->sex} <info>Created At:</info> {$_client->source_created_at} <info>Facility:</info> {$_client->facility_id}");

        return $_client;
    }
}