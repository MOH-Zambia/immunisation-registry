<?php

namespace App\Repositories;

use App\Models\Record;
use App\Repositories\BaseRepository;

/**
 * Class RecordRepository
 * @package App\Repositories
 * @version July 8, 2021, 12:52 am UTC
*/

class RecordRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
