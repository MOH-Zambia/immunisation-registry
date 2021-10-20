<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\BaseRepository;

/**
 * Class ClientRepository
 * @package App\Repositories
 * @version September 8, 2021, 6:38 am UTC
*/

class ClientRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_uid',
        'card_number',
        'NRC',
        'passport_number',
        'first_name',
        'last_name',
        'other_names',
        'sex',
        'date_of_birth',
        'place_of_birth',
        'occupation',
        'status',
        'contact_number',
        'contact_email_address',
        'address_line1',
        'address_line2',
        'next_of_kin_name',
        'next_of_kin_contact_number',
        'next_of_kin_contact_email_address',
        'nationality',
        'facility_id',
        'user_id',
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
        return Client::class;
    }
}
