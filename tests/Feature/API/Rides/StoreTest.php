<?php

declare(strict_types=1);

namespace Tests\Feature\API\Rides;

use App\Models\Decision;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    private const ENDPOINT = '/api/rides';

    public function testCanBookARide(): void
    {
        $decision = Decision::factory()->create(['is_active' => true]);

        $data = [
            'pickup_moment' => now()->addMonth()->format('Y-m-d H:i'),
            'to'            => 'Rotterdam, Blaak 1',
            'from'          => 'Barendrecht Stationsweg 14',
            'distance'      => 20,
            'resident_id'   => $decision->resident->id,
        ];

        $this->postJson(self::ENDPOINT, $data)
            ->assertStatus(201);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCanNotBookARide($data, $message, $errors): void
    {
        $decision = Decision::factory()->create(['balance' => 50, 'is_active' => true]);
        $data['resident_id'] = $decision->resident->id;

        $this->postJson(self::ENDPOINT, $data)
            ->assertJsonPath('message', $message)
            ->assertJsonValidationErrors($errors)
            ->assertStatus(422);
    }

    public function dataProvider()
    {
        return [
            [
                [
                    'to'       => 'Rotterdam, Blaak 1',
                    'from'     => 'Barendrecht Stationsweg 14',
                    'distance' => 20,
                ],
                'message' => 'The pickup moment field is required.',
                [
                    'pickup_moment' => 'The pickup moment field is required.',
                ],
            ],
            [
                [
                    'pickup_moment' => now()->addMonth()->format('Y-m-d H:i'),
                    'from'          => 'Barendrecht Stationsweg 14',
                    'distance'      => 20,
                ],
                'message' => 'The to field is required.',
                [
                    'to' => 'The to field is required.',
                ],
            ],
            [
                [
                    'pickup_moment' => now()->addMonth()->format('Y-m-d H:i'),
                    'to'            => 'Rotterdam, Blaak 1',
                    'distance'      => 20,
                ],
                'message' => 'The from field is required.',
                [
                    'from' => 'The from field is required.',
                ],
            ],
            [
                [
                    'pickup_moment' => now()->addMonth()->format('Y-m-d H:i'),
                    'to'            => 'Rotterdam, Blaak 1',
                    'from'          => 'Barendrecht Stationsweg 14',
                ],
                'message' => 'The distance field is required.',
                [
                    'distance' => 'The distance field is required.',
                ],
            ],
            [
                [
                    'pickup_moment' => now()->addMonth()->format('Y-m-d H:i'),
                    'to'            => 'Rotterdam, Blaak 1',
                    'from'          => 'Barendrecht Stationsweg 14',
                    'distance'      => 200,
                ],
                'message' => 'Ride can not be booked. The given distance exceeds budget. Current balance is 50 km.',
                [
                    'distance' => 'Ride can not be booked. The given distance exceeds budget. Current balance is 50 km.',
                ],
            ],
            [
                [
                    'pickup_moment' => now()->subMonth()->format('Y-m-d H:i'),
                    'to'            => 'Rotterdam, Blaak 1',
                    'from'          => 'Barendrecht Stationsweg 14',
                    'distance'      => 20,
                ],
                'message' => 'The pickup moment must be a date after today.',
                [
                    'pickup_moment' => 'The pickup moment must be a date after today.',
                ],
            ],
            [
                [
                    'pickup_moment' => 'invalid_date',
                    'to'            => 'Rotterdam, Blaak 1',
                    'from'          => 'Barendrecht Stationsweg 14',
                    'distance'      => 20,
                ],
                'message' => 'The pickup moment does not match the format Y-m-d H:i. (and 1 more error)',
                [
                    'pickup_moment' => ['The pickup moment does not match the format Y-m-d H:i.','The pickup moment must be a date after today.'],
                ],
            ],
        ];
    }

    public function testCanNotBookRideWhenResidentIsNotFound(): void
    {
        $data = [
            'pickup_moment' => now()->addMonth()->format('Y-m-d H:i'),
            'to'            => 'Rotterdam, Blaak 1',
            'from'          => 'Barendrecht Stationsweg 14',
            'distance'      => 20,
            'resident_id'   => 9999999,
        ];

        $this->postJson(self::ENDPOINT, $data)
            ->assertJsonPath('message', 'The provided resident does not exists.')
            ->assertJsonValidationErrors(['resident_id' => 'The provided resident does not exists.'])
            ->assertStatus(422);
    }

    public function testCanNotBookRideWhenDecisionIsNotActive(): void
    {
        $decision = Decision::factory()->create(['is_active' => false]);

        $data = [
            'pickup_moment' => now()->addMonth()->format('Y-m-d H:i'),
            'to'            => 'Rotterdam, Blaak 1',
            'from'          => 'Barendrecht Stationsweg 14',
            'distance'      => 20,
            'resident_id'   => $decision->resident->id,
        ];

        $this->postJson(self::ENDPOINT, $data)
            ->assertJsonPath('message',
                'Ride can not be booked. The decision of resident ' . $decision->resident->name . ' is not active.')
            ->assertStatus(422);
    }
}
