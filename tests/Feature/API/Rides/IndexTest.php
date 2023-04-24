<?php

declare(strict_types=1);

namespace Tests\Feature\API\Rides;

use App\Models\Account;
use App\Models\Resident;
use App\Models\Ride;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseTransactions;

    private const ENDPOINT = '/api/%s/rides';

    public function testCanFetchRidesAsATaxiFirm(): void
    {
        $account = Account::factory()->create([
            'area' => 20,
        ]);

        $resident = Resident::factory()->create(['area' => 20]);
        $rides = Ride::factory(10)->create(['resident_id' => $resident->id]);

        $response = $this->getJson(sprintf(self::ENDPOINT, $account->id));
        $response->assertStatus(200)->assertJsonCount(10, 'data');

        foreach ($rides as $ride) {
            $response->assertJsonFragment(['id' => $ride->id]);
        }
    }
}
