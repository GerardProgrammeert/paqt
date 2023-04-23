<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $resident_id
 * @property CarbonImmutable $pickup_moment
 * @property string $to
 * @property string $from
 * @property boolean $is_driven
 * @property number $distance
 */
class Ride extends Model
{
    use HasFactory;

    protected $casts = [
        'pickup_moment' => 'immutable_datetime',
        'is_driven'     => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'resident_id', 'id');
    }
}
