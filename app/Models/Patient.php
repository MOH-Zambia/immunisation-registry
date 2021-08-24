<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Patient",
 *      required={"patient_id", "first_name", "last_name", "other_names", "sex", "occupation", "status", "address_line1", "record_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="patient_id",
 *          description="patient_id",
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
 *      @SWG\Property(
 *          property="date_of_birh",
 *          description="date_of_birh",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="place_of_birth",
 *          description="place_of_birth",
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
 *      @SWG\Property(
 *          property="residence",
 *          description="residence",
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
class Patient extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'patients';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'patient_id',
        'first_name',
        'last_name',
        'other_names',
        'sex',
        'occupation',
        'status',
        'contact_number',
        'contact_email_address',
        'next_of_kin_name',
        'next_of_kin_contact_number',
        'next_of_kin_contact_email_address',
        'date_of_birh',
        'place_of_birth',
        'address_line1',
        'address_line2',
        'residence',
        'record_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'other_names' => 'string',
        'sex' => 'string',
        'occupation' => 'string',
        'status' => 'string',
        'contact_number' => 'string',
        'contact_email_address' => 'string',
        'next_of_kin_name' => 'string',
        'next_of_kin_contact_number' => 'string',
        'next_of_kin_contact_email_address' => 'string',
        'date_of_birh' => 'date',
        'place_of_birth' => 'string',
        'address_line1' => 'string',
        'address_line2' => 'string',
        'residence' => 'string',
        'record_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'patient_id' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'other_names' => 'required|string|max:255',
        'sex' => 'required|string|max:255',
        'occupation' => 'required|string|max:255',
        'status' => 'required|string|max:255',
        'contact_number' => 'nullable|string|max:255',
        'contact_email_address' => 'nullable|string|max:255',
        'next_of_kin_name' => 'nullable|string|max:255',
        'next_of_kin_contact_number' => 'nullable|string|max:255',
        'next_of_kin_contact_email_address' => 'nullable|string|max:255',
        'date_of_birh' => 'nullable',
        'place_of_birth' => 'nullable|string|max:255',
        'address_line1' => 'required|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'residence' => 'nullable|string|max:255',
        'record_id' => 'required|integer',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
