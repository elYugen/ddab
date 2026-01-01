<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-10 years', 'now');

        return [
            'company_id' => 1,

            'name' => fake()->randomElement([
                'Ambulance A',
                'Ambulance B',
                'VSL 1',
                'VSL 2',
                'SMUR 1',
            ]),

            'type' => fake()->randomElement([
                'ambulance',
                'vsl',
                'taxi',
            ]),

            'registration_number' => strtoupper(fake()->bothify('??-###-??')), 
            'vin_number' => fake()->bothify('?????????????????'), 

            'category' => fake()->randomElement([
                'A',
                'B',
                'C',
            ]),

            'in_service' => $inService = fake()->boolean(80),

            'service_start_date' => $start->format('Y-m-d'),
            'service_end_date' => $inService
                ? null
                : fake()->dateTimeBetween($start, 'now')->format('Y-m-d'),

            'ars_agreement_number' => fake()->numerify('##########'),
            'ars_agreement_start_date' => fake()->date('Y-m-d'),
            'ars_agreement_end_date'   => fake()->date('Y-m-d'),

        ];
    }
}
