<?php

namespace App\Repositories;

use App\Models\Province;
use App\Repositories\BaseRepository;

/**
 * Class ProvinceRepository
 * @package App\Repositories
 * @version August 19, 2021, 11:14 am UTC
*/

class ProvinceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'population',
        'pop_density',
        'area_sq_km',
        'geometry'
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
        return Province::class;
    }
}
