<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Vaccination",
 *      required={"client_id", "vaccine_id", "date", "dose_number", "vaccinating_organisation", "vaccinating_country_id", "event_uid", "record_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="client_id",
 *          description="client_id",
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
 *          property="dose_number",
 *          description="dose_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="date_of_next_dose",
 *          description="date_of_next_dose",
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
 *          property="vaccinating_organization",
 *          description="vaccinating_organization",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="vaccinating_country_id",
 *          description="vaccinating_country_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="vaccination_certificate_id",
 *          description="vaccination_certificate_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *     @SWG\Property(
 *          property="event_uid",
 *          description="event_uid",
 *          type="string",
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
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
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
        'client_id',
        'vaccine_id',
        'provider_id',
        'date',
        'dose_number',
        'date_of_next_dose',
        'type_of_strategy',
        'vaccine_batch_number',
        'vaccine_batch_expiration_date',
        'vaccinating_organization',
        'vaccinating_country_id',
        'vaccination_certificate_id',
        'facility_id',
        'event_uid',
        'record_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'client_id' => 'integer',
        'vaccine_id' => 'integer',
        'provider_id' => 'integer',
        'date' => 'date',
        'dose_number' => 'string',
        'date_of_next_dose' => 'date',
        'type_of_strategy' => 'string',
        'vaccine_batch_number' => 'string',
        'vaccine_batch_expiration_date' => 'date',
        'vaccinating_organization' => 'string',
        'vaccinating_country_id' => 'integer',
        'vaccination_certificate_id' => 'integer',
        'facility_id' => 'integer',
        'event_uid' => 'string',
        'record_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_id' => 'required|integer',
        'vaccine_id' => 'required|integer',
        'provider_id' => 'nullable|integer',
        'date' => 'required',
        'dose_number' => 'required|string|max:255',
        'date_of_next_dose' => 'nullable',
        'type_of_strategy' => 'nullable|string|max:255',
        'vaccine_batch_number' => 'nullable|string|max:255',
        'vaccine_batch_expiration_date' => 'nullable',
        'vaccinating_organization' => 'nullable|string|max:255',
        'vaccinating_country_id' => 'required|integer',
        'vaccination_certificate_id' => 'nullable|integer',
        'facility_id' => 'required|integer',
        'event_uid' => 'nullable|string|max:255',
        'record_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Provider::class, 'provider_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function facility(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Facility::class, 'facility_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Country::class, 'vaccinating_country_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function record(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Record::class, 'record_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function vaccine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Vaccine::class, 'vaccine_id');
    }
}
