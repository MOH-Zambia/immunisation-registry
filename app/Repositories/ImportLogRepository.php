<?php

namespace App\Repositories;

use App\Models\ImportLog;
use App\Repositories\BaseRepository;

/**
 * Class ImportLogRepository
 * @package App\Repositories
 * @version August 25, 2021, 7:24 am UTC
*/

class ImportLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'hash'
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
        return ImportLog::class;
    }
}
