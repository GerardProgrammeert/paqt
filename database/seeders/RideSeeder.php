<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ride;
use Illuminate\Database\Seeder;

class RideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ride::factory(10)->create();
    }
}
