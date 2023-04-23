<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Decision>
 */
class DecisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'resident_id'       => Resident::factory(),
            'prolongation_date' => fake()->dateTimeBetween('-2 week', 'now'),
            'is_active'         => fake()->boolean(),
            'balance'           => 1500,
        ];
    }
}
