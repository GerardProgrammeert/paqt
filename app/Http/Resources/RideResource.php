<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'resident'      => [
                'id'      => $this->resident->id,
                'name'    => $this->resident->name,
            ],
            'distance'      => $this->distance,
            'pickup_moment' => $this->pickup_moment->format('Y-m-d H:i'),
            'is_driven'     => $this->is_driven,
        ];
    }
}
