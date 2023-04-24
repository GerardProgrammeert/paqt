<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ride>
 */
class RideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'distance'      => fake()->randomNumber(2),
            'resident_id'   => Resident::all()->isNotEmpty() ? Resident::all()->random()->id :
                Resident::factory(),
            'pickup_moment' => fake()->dateTimeThisYear(),
            'to'            => fake()->address(),
            'from'          => fake()->address(),
            'is_driven'     => fake()->boolean(),
        ];
    }
}
