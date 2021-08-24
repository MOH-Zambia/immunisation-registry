<?php

namespace App\Repositories;

use App\Models\IDType;
use App\Repositories\BaseRepository;

/**
 * Class IDTypeRepository
 * @package App\Repositories
 * @version August 12, 2021, 1:34 pm UTC
*/

class IDTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'pattern'
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
        return IDType::class;
    }
}
