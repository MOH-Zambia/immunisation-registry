<?php

namespace App\Repositories;

use App\Models\Certificate;
use App\Repositories\BaseRepository;

/**
 * Class CertificateRepository
 * @package App\Repositories
 * @version July 8, 2021, 12:43 am UTC
*/

class CertificateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'vaccination_certificate_id',
        'signature_algorithm',
        'certificate_issuing_authority_id',
        'vaccination_certificate_batch_number',
        'patient_id',
        'certificate_expiration_date',
        'innoculated_since_date',
        'recovery_date',
        'patient_status',
        'dose_1_id',
        'dose_2_id',
        'dose_3_id',
        'dose_4_id',
        'qr_code',
        'certificate_url'
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
