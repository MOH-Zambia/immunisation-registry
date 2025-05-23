<?php

namespace App\Repositories;

use App\Models\Vaccination;
use App\Repositories\BaseRepository;

/**
 * Class VaccinationRepository
 * @package App\Repositories
 * @version August 30, 2021, 12:06 pm UTC
*/

class VaccinationRepository extends BaseRepository
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
        'vaccination_certificate_id'
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
        return Vaccination::class;
    }
}
