<?php

namespace Database\Factories;

use App\Models\Vaccine;
use Illuminate\Database\Eloquent\Factories\Factory;

class VaccineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vaccine::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_name' => $this->faker->word,
        'short_description' => $this->faker->word,
        'vaccine_code' => $this->faker->word,
        'vaccine_manufacturer' => $this->faker->word,
        'vaccine_type' => $this->faker->word,
        'commercial_formulation' => $this->faker->word,
        'vaccine_status' => $this->faker->word,
        'notes' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
