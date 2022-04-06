<?php

namespace App\Console\Commands;
use \DateTime;

/**
 * Helper Class Utilities.php
 *
 * This Class serves as the habour of properties and actions that are used in several classes within the module - Console.
 * @package IR
 * @subpackage Commands
 * @access public
 * @author Mullenga Chiwelle <Mulenga.Chiwele@MOH.gov.zm>
 * @copyright Copyright &copy; 2022 Ministry of Health, GRZ.
 * @since v1.0
 * @version v1.0
 */


class Utilities
{
    public function isDateValid($_date, $_format = 'Y-m-d')
    {
        $_temp_date = DateTime::createFromFormat($_format, $_date);
        return $_temp_date && $_temp_date->format($_format) === $_date;
    }

    public function getTimestampsDifferenceInSeconds($_start_datetime, $_end_datetime)
    {
        return strtotime($_end_datetime) - strtotime($_start_datetime);
    }
    
    public function getTrackedEntityInstance($_httpClient, $_tracked_entity_instance_uid)
    {
        $_response = $_httpClient->request('GET', env('DHIS2_BASE_URL'). "trackedEntityInstances.json?trackedEntityInstance={$_tracked_entity_instance_uid}", [
            'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
        ]);

        if ($_response->getStatusCode() == 200) {
            $_response_body = json_decode($_response->getBody(), true);
            return $_response_body['trackedEntityInstances'][0];
        };
        return json_encode(json_decode ("{}"));
    }
}