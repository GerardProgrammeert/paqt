<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Decision extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'prolongation_date' => 'immutable_date',
        'is_active'         => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }
}
