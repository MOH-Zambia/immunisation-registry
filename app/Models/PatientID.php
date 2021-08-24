<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="PatientID",
 *      required={"country_id", "expiration_date", "id_num", "id_type_id", "issue_date", "issue_place"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="country_id",
 *          description="country_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="expiration_date",
 *          description="expiration_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="id_num",
 *          description="id_num",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="id_type_id",
 *          description="id_type_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="issue_date",
 *          description="issue_date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="issue_place",
 *          description="issue_place",
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
class PatientID extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'patient_ids';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'country_id',
        'expiration_date',
        'id_num',
        'id_type_id',
        'issue_date',
        'issue_place'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'country_id' => 'integer',
        'expiration_date' => 'date',
        'id_num' => 'string',
        'id_type_id' => 'integer',
        'issue_date' => 'date',
        'issue_place' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'country_id' => 'required|integer',
        'expiration_date' => 'required',
        'id_num' => 'required|string|max:255',
        'id_type_id' => 'required|integer',
        'issue_date' => 'required',
        'issue_place' => 'required|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
