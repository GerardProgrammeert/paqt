<?php

namespace Tests\Feature\Commands;

use App\Models\Decision;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DecisionResetTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testCanResetActiveExpiredDecisions(): void
    {
        $decisions = Decision::factory(10)->create([
            'prolongation_date' => '2023-01-01',
            'is_active'         => true,
        ]);

        $this->artisan("decision:reset")
            ->expectsOutput('Start decision:reset command')
            ->expectsOutput('Number of decisions to be reset: 10')
            ->assertExitCode(0);

        foreach ($decisions as $decision) {
            $decision->refresh();
            $this->assertEquals('2024-01-01', $decision->prolongation_date->toDateString());
        }
    }

    public function testCanNotResetInactiveValidDecisions(): void
    {
        $futureDate = now()->addMonth()->format('Y-m-d');

        $decisions = Decision::factory(10)->create([
            'prolongation_date' => $futureDate,
            'is_active'         => false,
        ]);

        $this->artisan("decision:reset")
            ->expectsOutput('Start decision:reset command')
            ->assertExitCode(0);

        foreach ($decisions as $decision) {
            $this->assertEquals($futureDate, $decision->prolongation_date->toDateString());
        }
    }

    public function testCanNotResetAnyActiveValidDesicions(): void
    {
        $today = now()->format('Y-m-d');
        $decisions = Decision::factory(10)->create([
            'prolongation_date' => $today,
            'is_active'         => true,
        ]);

        $this->artisan("decision:reset")
            ->expectsOutput('Start decision:reset command')
            ->assertExitCode(0);

        foreach ($decisions as $decision) {
            $this->assertEquals($today, $decision->prolongation_date->toDateString());
        }
    }
}
