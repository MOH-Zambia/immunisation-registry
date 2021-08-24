<?php

namespace App\Repositories;

use App\Models\District;
use App\Repositories\BaseRepository;

/**
 * Class DistrictRepository
 * @package App\Repositories
 * @version August 19, 2021, 11:20 am UTC
*/

class DistrictRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'province_id',
        'name',
        'district_type',
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
        return District::class;
    }
}
