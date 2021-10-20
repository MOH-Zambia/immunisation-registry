<?php

namespace App\Repositories;

use App\Models\Vaccine;
use App\Repositories\BaseRepository;

/**
 * Class VaccineRepository
 * @package App\Repositories
 * @version August 30, 2021, 11:56 am UTC
*/

class VaccineRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_name',
        'short_description',
        'cdc_cvx_code',
        'vaccine_manufacturer',
        'cdc_mvx_code',
        'vaccine_group',
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
