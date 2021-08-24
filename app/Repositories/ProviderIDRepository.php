<?php

namespace App\Repositories;

use App\Models\ProviderID;
use App\Repositories\BaseRepository;

/**
 * Class ProviderIDRepository
 * @package App\Repositories
 * @version July 8, 2021, 12:57 am UTC
*/

class ProviderIDRepository extends BaseRepository
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
        return ProviderID::class;
    }
}
