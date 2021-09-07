<?php

namespace App\Repositories;

use App\Models\Record;
use App\Repositories\BaseRepository;

/**
 * Class RecordRepository
 * @package App\Repositories
 * @version August 26, 2021, 5:17 pm UTC
*/

class RecordRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'data_source',
        'data_type',
        'data'
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
        return Record::class;
    }
}
