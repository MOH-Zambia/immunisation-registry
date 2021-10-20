<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

/**
 * @SWG\Definition(
 *      definition="Facility",
 *      required={"district_id", "name", "facility_type", "ownership"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="facility_id",
 *          description="facility_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="HMIS_code",
 *          description="HMIS_code",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="DHIS2_UID",
 *          description="DHIS2_UID",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="smartcare_GUID",
 *          description="smartcare_GUID",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="eLMIS_ID",
 *          description="eLMIS_ID",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="iHRIS_ID",
 *          description="iHRIS_ID",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="district_id",
 *          description="district_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="facility_type",
 *          description="facility_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="ownership",
 *          description="ownership",
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
 *          property="catchment_population_head_count",
 *          description="catchment_population_head_count",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="catchment_population_cso",
 *          description="catchment_population_cso",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="operation_status",
 *          description="operation_status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="location",
 *          description="location",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="location_type",
 *          description="location_type",
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
 * @property \Grimzy\LaravelMysqlSpatial\Types\Point $location
 */
class Facility extends Model
{
    use SpatialTrait;
    use SoftDeletes;
    use HasFactory;

    public $table = 'facilities';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $spatialFields = [
        'location',
    ];

    public $fillable = [
        'facility_id',
        'HMIS_code',
        'DHIS2_UID',
        'smartcare_GUID',
        'eLMIS_ID',
        'iHRIS_ID',
        'district_id',
        'name',
        'facility_type',
        'ownership',
        'address_line1',
        'address_line2',
        'catchment_population_head_count',
        'catchment_population_cso',
        'operation_status',
        'location',
        'location_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'facility_id' => 'integer',
        'HMIS_code' => 'string',
        'DHIS2_UID' => 'string',
        'smartcare_GUID' => 'string',
        'eLMIS_ID' => 'string',
        'iHRIS_ID' => 'string',
        'district_id' => 'integer',
        'name' => 'string',
        'facility_type' => 'string',
        'ownership' => 'string',
        'address_line1' => 'string',
        'address_line2' => 'string',
        'catchment_population_head_count' => 'integer',
        'catchment_population_cso' => 'integer',
        'operation_status' => 'string',
        'location' => 'string',
        'location_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'facility_id' => 'nullable|integer',
        'HMIS_code' => 'nullable|string|max:255',
        'DHIS2_UID' => 'nullable|string|max:255',
        'smartcare_GUID' => 'nullable|string|max:255',
        'eLMIS_ID' => 'nullable|string|max:255',
        'iHRIS_ID' => 'nullable|string|max:255',
        'district_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'facility_type' => 'required|string|max:255',
        'ownership' => 'required|string|max:255',
        'address_line1' => 'nullable|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'catchment_population_head_count' => 'nullable|integer',
        'catchment_population_cso' => 'nullable|integer',
        'operation_status' => 'nullable|string|max:255',
        'location' => 'nullable|string',
        'location_type' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function __toString()
    {
        try
        {
            return "Facility ID: $this->id, Facility name: $this->name, District: $this->district->name, Provinve: $this->district->province->name";
        }
        catch (Exception $exception)
        {
            return '';
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function district()
    {
        return $this->belongsTo(\App\Models\District::class, 'district_id');
    }
}
