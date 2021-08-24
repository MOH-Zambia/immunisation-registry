<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'patient_id' => $this->faker->word,
        'first_name' => $this->faker->word,
        'last_name' => $this->faker->word,
        'other_names' => $this->faker->word,
        'sex' => $this->faker->word,
        'occupation' => $this->faker->word,
        'status' => $this->faker->word,
        'contact_number' => $this->faker->word,
        'contact_email_address' => $this->faker->word,
        'next_of_kin_name' => $this->faker->word,
        'next_of_kin_contact_number' => $this->faker->word,
        'next_of_kin_contact_email_address' => $this->faker->word,
        'date_of_birh' => $this->faker->word,
        'place_of_birth' => $this->faker->word,
        'address_line1' => $this->faker->word,
        'address_line2' => $this->faker->word,
        'residence' => $this->faker->word,
        'record_id' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
