<?php

namespace Database\Factories;

use App\Models\ProviderID;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderIDFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProviderID::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'country_id' => $this->faker->randomDigitNotNull,
        'expiration_date' => $this->faker->word,
        'id_num' => $this->faker->word,
        'id_type_id' => $this->faker->randomDigitNotNull,
        'issue_date' => $this->faker->word,
        'issue_place' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
