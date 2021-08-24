<?php

namespace App\Repositories;

use App\Models\Facility;
use App\Repositories\BaseRepository;

/**
 * Class FacilityRepository
 * @package App\Repositories
 * @version August 12, 2021, 2:40 pm UTC
*/

class FacilityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'facility_id',
        'HMIS_code',
        'DHIS2_UID',
        'smartcare_GUID',
        'eLMIS_ID',
        'iHRIS_ID',
        'district_id',
        'name',
        'facility_type',
        'ownership',
        'address_line1',
        'address_line2',
        'catchment_population_head_count',
        'catchment_population_cso',
        'operation_status',
        'location',
        'location_type'
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
        return Facility::class;
    }
}
