<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_role_id' => 3,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail(),
            'email_code' => Str::random(10),
            'email_verified_at' => now(),
            'last_login' => now(),
            'password'  => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'ip' => $this->faker->ipv4,
            'salt' => Str::random(10),
//            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'created_at' => $this->faker->dateTimeBetween('-9 month', '+1 month'),
        ];
    }
}