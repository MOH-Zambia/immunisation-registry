<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * @SWG\Definition(
 *      definition="Province",
 *      required={"name", "geometry"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
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
 *
 * @property \Grimzy\LaravelMysqlSpatial\Types\Polygon $geometry
 */
class Province extends Model
{
    use SpatialTrait;
    use SoftDeletes;
    use HasFactory;

    public $table = 'provinces';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $spatialFields = [
        'geometry',
    ];

    public $fillable = [
        'name',
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
        'name' => 'string',
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
        'name' => 'required|string|max:255',
        'population' => 'nullable|integer',
        'pop_density' => 'nullable|numeric',
        'area_sq_km' => 'nullable|numeric',
        'geometry' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function districts()
    {
        return $this->hasMany(\App\Models\District::class, 'province_id');
    }
}
