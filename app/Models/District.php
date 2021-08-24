<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * @SWG\Definition(
 *      definition="District",
 *      required={"province_id", "name", "geometry"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="province_id",
 *          description="province_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="district_type",
 *          description="district_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="population",
 *          description="population",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="pop_density",
 *          description="pop_density",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="area_sq_km",
 *          description="area_sq_km",
 *          type="number",
 *          format="number"
 *      ),
 *      @SWG\Property(
 *          property="geometry",
 *          description="geometry",
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
class District extends Model
{
    use SoftDeletes;

    use HasFactory;

    use SpatialTrait;

    public $table = 'districts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $spatialFields = [
        'geometry',
    ];

    public $fillable = [
        'province_id',
        'name',
        'district_type',
        'population',
        'pop_density',
        'area_sq_km',
        'geometry'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'province_id' => 'integer',
        'name' => 'string',
        'district_type' => 'string',
        'population' => 'integer',
        'pop_density' => 'float',
        'area_sq_km' => 'float',
        'geometry' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'province_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'district_type' => 'nullable|string|max:255',
        'population' => 'nullable|integer',
        'pop_density' => 'nullable|numeric',
        'area_sq_km' => 'nullable|numeric',
        'geometry' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}
