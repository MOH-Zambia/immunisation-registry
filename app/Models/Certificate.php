<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Certificate",
 *      required={"certificate_uuid", "client_id", "target_disease", "certificate_status", "qr_code", "qr_code_path", "certificate_url"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="certificate_uuid",
 *          description="certificate_uuid",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="client_id",
 *          description="client_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *     @SWG\Property(
 *          property="trusted_vaccine_code",
 *          description="trusted_vaccine_code",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="target_disease",
 *          description="target_disease",
 *          type="string",
 *      ),
 *     @SWG\Property(
 *          property="certificate_expiration_date",
 *          description="certificate_expiration_date",
 *          type="string",
 *          format="date"
 *      ),
 *     @SWG\Property(
 *          property="certificate_status",
 *          description="certificate_status",
 *          type="string"
 *      ),
 *     @SWG\Property(
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
 *          property="qr_code",
 *          description="qr_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="qr_code_path",
 *          description="qr_code_path",
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
        'certificate_uuid',
        'client_id',
        'trusted_vaccine_code',
        'target_disease',
        'certificate_expiration_date',
        'certificate_status',
        'signature_algorithm',
        'certificate_issuing_authority_id',
        'vaccination_certificate_batch_number',
        'qr_code',
        'qr_code_path',
        'certificate_url'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'certificate_uuid' => 'string',
        'client_id' => 'integer',
        'trusted_vaccine_code' => 'string',
        'target_disease',
        'certificate_expiration_date' => 'date',
        'certificate_status' => 'string',
        'signature_algorithm' => 'string',
        'certificate_issuing_authority_id' => 'integer',
        'vaccination_certificate_batch_number' => 'string',
        'qr_code' => 'string',
        'qr_code_path' => 'string',
        'certificate_url' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'certificate_uuid' => 'required|string|max:36',
        'client_id' => 'required|integer',
        'trusted_vaccine_code' => 'nullable|string|max:255',
        'target_disease' => 'required|string|max:255',
        'certificate_expiration_date' => 'nullable',
        'certificate_status' => 'required|string|max:255',
        'signature_algorithm' => 'nullable|string|max:255',
        'certificate_issuing_authority_id' => 'nullable|integer',
        'vaccination_certificate_batch_number' => 'nullable|string|max:255',
        'qr_code' => 'required|string|max:65535',
        'qr_code_path' => 'required|string|max:255',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function vaccinations()
    {
        return $this->hasMany(\App\Models\Vaccination::class, 'certificate_id')->orderBy('date');;
    }
}
