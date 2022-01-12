<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @SWG\Definition(
 *      definition="Record",
 *      required={"record_id", "data_source", "data_type", "hash", "data"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *     @SWG\Property(
 *          property="record_id",
 *          description="record_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="data_source",
 *          description="data_source",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="data_type",
 *          description="data_type",
 *          type="string"
 *      ),
 *     @SWG\Property(
 *          property="hash",
 *          description="hash",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="data",
 *          description="data",
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
class Record extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'records';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'record_id',
        'data_source',
        'data_type',
        'hash',
        'data'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'record_id' => 'string',
        'data_source' => 'string',
        'data_type' => 'string',
        'hash' => 'string',
        'data' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'record_id' => 'required|string|max:255',
        'data_source' => 'required|string|max:255',
        'data_type' => 'required|string|max:255',
        'hash' => 'required|string|max:255',
        'data' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function clients()
    {
        return $this->hasMany(\App\Models\Client::class, 'record_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function vaccinations()
    {
        return $this->hasMany(\App\Models\Vaccination::class, 'record_id');
    }
}
