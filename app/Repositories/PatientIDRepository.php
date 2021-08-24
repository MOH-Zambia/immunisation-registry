<?php

namespace App\Repositories;

use App\Models\PatientID;
use App\Repositories\BaseRepository;

/**
 * Class PatientIDRepository
 * @package App\Repositories
 * @version August 12, 2021, 12:05 pm UTC
*/

class PatientIDRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'country_id',
        'expiration_date',
        'id_num',
        'id_type_id',
        'issue_date',
        'issue_place'
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
        return PatientID::class;
    }
}
