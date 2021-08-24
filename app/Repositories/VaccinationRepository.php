<?php

namespace App\Repositories;

use App\Models\Vaccination;
use App\Repositories\BaseRepository;

/**
 * Class VaccinationRepository
 * @package App\Repositories
 * @version July 8, 2021, 12:49 am UTC
*/

class VaccinationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'patient_id',
        'vaccine_id',
        'provider_id',
        'date',
        'type_of_strategy',
        'vaccine_batch_number',
        'vaccine_batch_expiration_date',
        'vaccinating_organization_id',
        'vaccinating_country',
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
        return Vaccination::class;
    }
}
