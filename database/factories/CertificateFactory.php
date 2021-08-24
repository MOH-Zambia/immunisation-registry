<?php

namespace Database\Factories;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Certificate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vaccination_certificate_id' => $this->faker->word,
        'signature_algorithm' => $this->faker->word,
        'certificate_issuing_authority_id' => $this->faker->randomDigitNotNull,
        'vaccination_certificate_batch_number' => $this->faker->word,
        'patient_id' => $this->faker->randomDigitNotNull,
        'certificate_expiration_date' => $this->faker->word,
        'innoculated_since_date' => $this->faker->word,
        'recovery_date' => $this->faker->word,
        'patient_status' => $this->faker->word,
        'dose_1_id' => $this->faker->randomDigitNotNull,
        'dose_2_id' => $this->faker->randomDigitNotNull,
        'dose_3_id' => $this->faker->randomDigitNotNull,
        'dose_4_id' => $this->faker->randomDigitNotNull,
        'qr_code' => $this->faker->word,
        'certificate_url' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
