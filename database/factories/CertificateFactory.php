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
            'certificate_uuid' => $this->faker->word,
        'signature_algorithm' => $this->faker->word,
        'certificate_issuing_authority_id' => $this->faker->randomDigitNotNull,
        'vaccination_certificate_batch_number' => $this->faker->word,
        'client_id' => $this->faker->randomDigitNotNull,
        'certificate_expiration_date' => $this->faker->word,
        'innoculated_since_date' => $this->faker->word,
        'recovery_date' => $this->faker->word,
        'client_status' => $this->faker->word,
        'dose_1_date' => $this->faker->word,
        'dose_2_date' => $this->faker->word,
        'dose_3_date' => $this->faker->word,
        'dose_4_date' => $this->faker->word,
        'dose_5_date' => $this->faker->word,
        'booster_dose_date' => $this->faker->word,
        'qr_code' => $this->faker->word,
        'qr_code_path' => $this->faker->word,
        'certificate_url' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s'),
        'deleted_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
