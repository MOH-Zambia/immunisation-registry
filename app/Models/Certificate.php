<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Certificate",
 *      required={"vaccination_certificate_id", "signature_algorithm", "certificate_issuing_authority_id", "patient_id", "certificate_expiration_date", "dose_1_id", "qr_code", "certificate_url"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="vaccination_certificate_id",
 *          description="vaccination_certificate_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="signature_algorithm",
 *          description="signature_algorithm",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="certificate_issuing_authority_id",
 *          description="certificate_issuing_authority_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="vaccination_certificate_batch_number",
 *          description="vaccination_certificate_batch_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="patient_id",
 *          description="patient_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="certificate_expiration_date",
 *          description="certificate_expiration_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="innoculated_since_date",
 *          description="innoculated_since_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="recovery_date",
 *          description="recovery_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="patient_status",
 *          description="patient_status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="dose_1_id",
 *          description="dose_1_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="dose_2_id",
 *          description="dose_2_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="dose_3_id",
 *          description="dose_3_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="dose_4_id",
 *          description="dose_4_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="qr_code",
 *          description="qr_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="certificate_url",
 *          description="certificate_url",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Certificate extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'certificates';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'vaccination_certificate_id' => 'string',
        'signature_algorithm' => 'string',
        'certificate_issuing_authority_id' => 'integer',
        'vaccination_certificate_batch_number' => 'string',
        'patient_id' => 'integer',
        'certificate_expiration_date' => 'date',
        'innoculated_since_date' => 'date',
        'recovery_date' => 'date',
        'patient_status' => 'string',
        'dose_1_id' => 'integer',
        'dose_2_id' => 'integer',
        'dose_3_id' => 'integer',
        'dose_4_id' => 'integer',
        'qr_code' => 'string',
        'certificate_url' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'vaccination_certificate_id' => 'required|string|max:36',
        'signature_algorithm' => 'required|string|max:255',
        'certificate_issuing_authority_id' => 'required|integer',
        'vaccination_certificate_batch_number' => 'nullable|string|max:255',
        'patient_id' => 'required|integer',
        'certificate_expiration_date' => 'required',
        'innoculated_since_date' => 'nullable',
        'recovery_date' => 'nullable',
        'patient_status' => 'nullable|string|max:255',
        'dose_1_id' => 'required|integer',
        'dose_2_id' => 'nullable|integer',
        'dose_3_id' => 'nullable|integer',
        'dose_4_id' => 'nullable|integer',
        'qr_code' => 'required|string|max:65535',
        'certificate_url' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
