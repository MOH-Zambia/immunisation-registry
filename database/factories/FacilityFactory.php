<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Facility::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'facility_id' => $this->faker->randomDigitNotNull,
        'HMIS_code' => $this->faker->word,
        'DHIS2_UID' => $this->faker->word,
        'smartcare_GUID' => $this->faker->word,
        'eLMIS_ID' => $this->faker->word,
        'iHRIS_ID' => $this->faker->word,
        'district_id' => $this->faker->randomDigitNotNull,
        'name' => $this->faker->word,
        'facility_type' => $this->faker->word,
        'ownership' => $this->faker->word,
        'address_line1' => $this->faker->word,
        'address_line2' => $this->faker->word,
        'catchment_population_head_count' => $this->faker->randomDigitNotNull,
        'catchment_population_cso' => $this->faker->randomDigitNotNull,
        'operation_status' => $this->faker->word,
        'location' => $this->faker->word,
        'location_type' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'deleted_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
