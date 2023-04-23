<?php

declare(strict_types=1);

namespace Tests\Feature\API\Residents;

use App\Models\Decision;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseMigrations,DatabaseTransactions;

    private const ENDPOINT = '/api/residents';

    public function testCanFetchResidents(): void
    {
        $decisions = Decision::factory(10)->create();

        $response = $this->getJson(self::ENDPOINT);

        $response->assertJsonCount(10,'data')
            ->assertStatus(200);

        foreach($decisions as $decision){
            $response->assertJsonFragment(['id' => $decision->resident->id]);
        }
    }
}
