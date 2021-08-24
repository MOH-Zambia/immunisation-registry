<?php

namespace App\Repositories;

use App\Models\Vaccine;
use App\Repositories\BaseRepository;

/**
 * Class VaccineRepository
 * @package App\Repositories
 * @version July 8, 2021, 12:41 am UTC
*/

class VaccineRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_name',
        'short_description',
        'vaccine_code',
        'vaccine_manufacturer',
        'vaccine_type',
        'commercial_formulation',
        'vaccine_status',
        'notes'
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
        return Vaccine::class;
    }
}
