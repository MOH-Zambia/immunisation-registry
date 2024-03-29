<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Client",
 *      required={"source_id", "first_name", "last_name", "sex", "status", "facility_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="source_id",
 *          description="client_uid",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="card_number",
 *          description="card_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="NRC",
 *          description="NRC",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="passport_number",
 *          description="passport_number",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="drivers_license",
 *          description="drivers_license",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="first_name",
 *          description="first_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="last_name",
 *          description="last_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="other_names",
 *          description="other_names",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="sex",
 *          description="sex",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="date_of_birth",
 *          description="date_of_birth",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="place_of_birth",
 *          description="place_of_birth",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="occupation",
 *          description="occupation",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="contact_number",
 *          description="contact_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="contact_email_address",
 *          description="contact_email_address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="address_line1",
 *          description="address_line1",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="address_line2",
 *          description="address_line2",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="guardian_name",
 *          description="guardian_name",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="guardian_NRC",
 *          description="guardian_NRC",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="guardian_passport_number",
 *          description="guardian_passport_number",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="guardian_contact_number",
 *          description="guardian_contact_number",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="guardian_contact_email_address",
 *          description="guardian_contact_email_address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="next_of_kin_name",
 *          description="next_of_kin_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="next_of_kin_contact_number",
 *          description="next_of_kin_contact_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="next_of_kin_contact_email_address",
 *          description="next_of_kin_contact_email_address",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="nationality",
 *          description="nationality",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="facility_id",
 *          description="facility_id",
 *          type="integer",
 *          format="int32"
 *      ),

 *     @SWG\Property(
 *          property="source_created_at",
 *          description="source_created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="source_updated_at",
 *          description="source_updated_at",
 *          type="string",
 *          format="date-time"
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
class Client extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'clients';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'client_uid',
        'card_number',
        'NRC',
        'passport_number',
        'drivers_license',
        'first_name',
        'last_name',
        'other_names',
        'sex',
        'date_of_birth',
        'place_of_birth',
        'occupation',
        'status',
        'contact_number',
        'contact_email_address',
        'address_line1',
        'address_line2',
        'guardian_name',
        'guardian_NRC',
        'guardian_passport_number',
        'guardian_contact_number',
        'guardian_contact_email_address',
        'next_of_kin_name',
        'next_of_kin_contact_number',
        'next_of_kin_contact_email_address',
        'nationality',
        'facility_id',
        'source_created_at',
        'source_updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'client_uid' => 'string',
        'card_number' => 'string',
        'NRC' => 'string',
        'passport_number' => 'string',
        'drivers_license' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'other_names' => 'string',
        'sex' => 'string',
        'date_of_birth' => 'date',
        'place_of_birth' => 'string',
        'occupation' => 'string',
        'status' => 'string',
        'contact_number' => 'string',
        'contact_email_address' => 'string',
        'address_line1' => 'string',
        'address_line2' => 'string',
        'guardian_name' => 'string',
        'guardian_NRC' => 'string',
        'guardian_passport_number' => 'string',
        'guardian_contact_number' => 'string',
        'guardian_contact_email_address' => 'string',
        'next_of_kin_name' => 'string',
        'next_of_kin_contact_number' => 'string',
        'next_of_kin_contact_email_address' => 'string',
        'nationality' => 'integer',
        'facility_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_uid' => 'required|string|max:255',
        'card_number' => 'nullable|string|max:255',
        'NRC' => 'nullable|string|max:255',
        'passport_number' => 'nullable|string|max:255',
        'drivers_license' => 'nullable|string|max:255',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'other_names' => 'nullable|string|max:255',
        'sex' => 'required|string|max:255',
        'date_of_birth' => 'nullable',
        'place_of_birth' => 'nullable|string|max:255',
        'occupation' => 'nullable|string|max:255',
        'status' => 'required|string|max:255',
        'contact_number' => 'nullable|string|max:255',
        'contact_email_address' => 'nullable|string|max:255',
        'address_line1' => 'nullable|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'guardian_name' => 'nullable|string|max:255',
        'guardian_NRC' => 'nullable|string|max:255',
        'guardian_passport_number' => 'nullable|string|max:255',
        'guardian_contact_number' => 'nullable|string|max:255',
        'guardian_contact_email_address' => 'nullable|string|max:255',
        'next_of_kin_contact_number' => 'nullable|string|max:255',
        'next_of_kin_contact_email_address' => 'nullable|string|max:255',
        'nationality' => 'required|integer',
        'facility_id' => 'required|integer',
        'source_created_at' => 'nullable',
        'source_updated_at' => 'nullable',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function country()
    {
        return $this->belongsTo(\App\Models\Country::class, 'nationality');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function facility()
    {
        return $this->belongsTo(\App\Models\Facility::class, 'facility_id');
    }

    /**
     * Get the user for the client account.
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function user()
    {
        return $this->hasOne(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class, 'client_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function vaccinations()
    {
        return $this->hasMany(\App\Models\Vaccination::class, 'client_id');
    }
}
