<?php

namespace Database\Factories;

use App\Models\Vaccinations;
use Illuminate\Database\Eloquent\Factories\Factory;

class VaccinationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vaccinations::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => $this->faker->randomDigitNotNull,
        'vaccine_id' => $this->faker->randomDigitNotNull,
        'provider_id' => $this->faker->randomDigitNotNull,
        'date' => $this->faker->word,
        'dose_number' => $this->faker->word,
        'date_of_next_dose' => $this->faker->word,
        'type_of_strategy' => $this->faker->word,
        'vaccine_batch_number' => $this->faker->word,
        'vaccine_batch_expiration_date' => $this->faker->word,
        'vaccinating_organization_id' => $this->faker->word,
        'vaccinating_country_id' => $this->faker->randomDigitNotNull,
        'certificate_id' => $this->faker->randomDigitNotNull,
        'facility_id' => $this->faker->randomDigitNotNull,
        'event_id' => $this->faker->word,
        'record_id' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'deleted_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
