<?php

declare(strict_types=1);

namespace Tests\Feature\API\Residents;

use App\Models\Decision;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseTransactions;

    private const ENDPOINT = '/api/residents';

    public function testCanFetchResidents(): void
    {
        $decisions = Decision::all();

        $response = $this->getJson(self::ENDPOINT);

        $response->assertJsonCount(count($decisions),'data')
            ->assertStatus(200);

        foreach($decisions as $decision){
            $response->assertJsonFragment(['id' => $decision->resident->id]);
        }
    }
}
