<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Certificate",
 *      required={"vaccination_certificate_id", "signature_algorithm", "certificate_issuing_authority_id", "client_id", "certificate_expiration_date", "dose_1_date", "qr_code", "certificate_url"},
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
 *          property="client_id",
 *          description="client_id",
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
 *          property="client_status",
 *          description="client_status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="dose_1_date",
 *          description="dose_1_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="dose_2_date",
 *          description="dose_2_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="dose_3_date",
 *          description="dose_3_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="dose_4_date",
 *          description="dose_4_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="dose_5_date",
 *          description="dose_5_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="booster_dose_date",
 *          description="booster_dose_date",
 *          type="string",
 *          format="date"
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
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
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
        'client_id' => 'integer',
        'certificate_expiration_date' => 'date',
        'innoculated_since_date' => 'date',
        'recovery_date' => 'date',
        'client_status' => 'string',
        'dose_1_date' => 'date',
        'dose_2_date' => 'date',
        'dose_3_date' => 'date',
        'dose_4_date' => 'date',
        'dose_5_date' => 'date',
        'booster_dose_date' => 'date',
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
        'client_id' => 'required|integer',
        'certificate_expiration_date' => 'required',
        'innoculated_since_date' => 'nullable',
        'recovery_date' => 'nullable',
        'client_status' => 'nullable|string|max:255',
        'dose_1_date' => 'required',
        'dose_2_date' => 'nullable',
        'dose_3_date' => 'nullable',
        'dose_4_date' => 'nullable',
        'dose_5_date' => 'nullable',
        'booster_dose_date' => 'nullable',
        'qr_code' => 'required|string|max:65535',
        'certificate_url' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }
}
