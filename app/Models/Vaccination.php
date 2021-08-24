<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Vaccination",
 *      required={"patient_id", "vaccine_id", "provider_id", "date", "type_of_strategy", "vaccine_batch_number", "vaccinating_organization_id", "vaccinating_country", "record_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="patient_id",
 *          description="patient_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="vaccine_id",
 *          description="vaccine_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="provider_id",
 *          description="provider_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="date",
 *          description="date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="type_of_strategy",
 *          description="type_of_strategy",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="vaccine_batch_number",
 *          description="vaccine_batch_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="vaccine_batch_expiration_date",
 *          description="vaccine_batch_expiration_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="vaccinating_organization_id",
 *          description="vaccinating_organization_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="vaccinating_country",
 *          description="vaccinating_country",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="record_id",
 *          description="record_id",
 *          type="integer",
 *          format="int32"
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
class Vaccination extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'vaccinations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'patient_id',
        'vaccine_id',
        'provider_id',
        'date',
        'type_of_strategy',
        'vaccine_batch_number',
        'vaccine_batch_expiration_date',
        'vaccinating_organization_id',
        'vaccinating_country',
        'record_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'vaccine_id' => 'integer',
        'provider_id' => 'integer',
        'date' => 'date',
        'type_of_strategy' => 'string',
        'vaccine_batch_number' => 'string',
        'vaccine_batch_expiration_date' => 'date',
        'vaccinating_organization_id' => 'string',
        'vaccinating_country' => 'string',
        'record_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'patient_id' => 'required|integer',
        'vaccine_id' => 'required|integer',
        'provider_id' => 'required|integer',
        'date' => 'required',
        'type_of_strategy' => 'required|string|max:255',
        'vaccine_batch_number' => 'required|string|max:255',
        'vaccine_batch_expiration_date' => 'nullable',
        'vaccinating_organization_id' => 'required|string|max:255',
        'vaccinating_country' => 'required|string|max:255',
        'record_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
