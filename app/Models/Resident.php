<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property string $address
 */
class Resident extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function rides(): HasMany
    {
        return $this->HasMany(Ride::class);
    }

    /**
     * @return HasOne
     */
    public function decision(): HasOne
    {
        return $this->hasOne(Decision::class);
    }
}
