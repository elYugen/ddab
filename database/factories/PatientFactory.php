<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => 1, // 1-5 aléatoire
            'first_name' => fake('fr_FR')->firstName(),
            'last_name' => fake('fr_FR')->lastName(),
            'birth_date' => fake('fr_FR')->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'), // Âge 18-60 ans
            'social_security_number' => fake('fr_FR')->numerify('1 ## ## ## ### ###'), // format français
            'street' => fake('fr_FR')->streetAddress(),
            'postal_code' => fake('fr_FR')->postcode(),
            'city' => fake('fr_FR')->city(),
            'phone' => fake('fr_FR')->phoneNumber(),
            //'deleted' => fake()->boolean(10), // 10% de chance true
        ];
    }
}
