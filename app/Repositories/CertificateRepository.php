<?php

namespace App\Repositories;

use App\Models\Certificate;
use App\Repositories\BaseRepository;

/**
 * Class CertificateRepository
 * @package App\Repositories
 * @version August 30, 2021, 11:42 am UTC
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
        'client_id',
        'certificate_expiration_date',
        'innoculated_since_date',
        'recovery_date',
        'client_status',
        'dose_1_date',
        'dose_2_date',
        'dose_3_date',
        'dose_4_date',
        'dose_5_date',
        'booster_dose_date',
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
