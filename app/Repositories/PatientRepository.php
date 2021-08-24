<?php

namespace App\Repositories;

use App\Models\Patient;
use App\Repositories\BaseRepository;

/**
 * Class PatientRepository
 * @package App\Repositories
 * @version July 8, 2021, 12:42 am UTC
*/

class PatientRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'patient_id',
        'first_name',
        'last_name',
        'other_names',
        'sex',
        'occupation',
        'status',
        'contact_number',
        'contact_email_address',
        'next_of_kin_name',
        'next_of_kin_contact_number',
        'next_of_kin_contact_email_address',
        'date_of_birh',
        'place_of_birth',
        'address_line1',
        'address_line2',
        'residence',
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
        return Patient::class;
    }
}
