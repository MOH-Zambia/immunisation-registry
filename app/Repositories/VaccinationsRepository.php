<?php

namespace App\Repositories;

use App\Models\Vaccinations;
use App\Repositories\BaseRepository;

/**
 * Class VaccinationsRepository
 * @package App\Repositories
 * @version September 8, 2021, 8:12 am UTC
*/

class VaccinationsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_id',
        'vaccine_id',
        'provider_id',
        'date',
        'dose_number',
        'date_of_next_dose',
        'type_of_strategy',
        'vaccine_batch_number',
        'vaccine_batch_expiration_date',
        'vaccinating_organization_id',
        'vaccinating_country_id',
        'certificate_id',
        'facility_id',
        'event_id',
        'record_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Vaccinations::class;
    }
}
