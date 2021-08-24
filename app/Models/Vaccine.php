<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Vaccine",
 *      required={"product_name", "short_description", "vaccine_code", "vaccine_manufacturer", "vaccine_type", "commercial_formulation", "vaccine_status"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="product_name",
 *          description="product_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="short_description",
 *          description="short_description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="vaccine_code",
 *          description="vaccine_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="vaccine_manufacturer",
 *          description="vaccine_manufacturer",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="vaccine_type",
 *          description="vaccine_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="commercial_formulation",
 *          description="commercial_formulation",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="vaccine_status",
 *          description="vaccine_status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="notes",
 *          description="notes",
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
class Vaccine extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'vaccines';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'product_name',
        'short_description',
        'vaccine_code',
        'vaccine_manufacturer',
        'vaccine_type',
        'commercial_formulation',
        'vaccine_status',
        'notes'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'product_name' => 'string',
        'short_description' => 'string',
        'vaccine_code' => 'string',
        'vaccine_manufacturer' => 'string',
        'vaccine_type' => 'string',
        'commercial_formulation' => 'string',
        'vaccine_status' => 'string',
        'notes' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_name' => 'required|string|max:255',
        'short_description' => 'required|string|max:255',
        'vaccine_code' => 'required|string|max:255',
        'vaccine_manufacturer' => 'required|string|max:255',
        'vaccine_type' => 'required|string|max:255',
        'commercial_formulation' => 'required|string|max:255',
        'vaccine_status' => 'required|string|max:255',
        'notes' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    
}
