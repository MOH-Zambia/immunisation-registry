<?php

namespace App\Repositories;

use App\Models\Provider;
use App\Repositories\BaseRepository;

/**
 * Class ProviderRepository
 * @package App\Repositories
 * @version July 8, 2021, 12:36 am UTC
*/

class ProviderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'first_name',
        'last_name',
        'other_names',
        'sex'
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
        return Provider::class;
    }
}
