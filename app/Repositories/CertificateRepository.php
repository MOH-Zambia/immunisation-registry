<?php

namespace App\Repositories;

use App\Models\Certificate;
use App\Repositories\BaseRepository;

/**
 * Class CertificateRepository
 * @package App\Repositories
 * @version September 8, 2021, 6:37 am UTC
*/

class CertificateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'certificate_uuid',
        'client_id',
        'trusted_vaccine_code',
        'certificate_issuing_authority_id',
        'vaccination_certificate_batch_number',
        'target_disease',
        'certificate_expiration_date'
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
        return Certificate::class;
    }
}
