<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoverResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'planet_id' => $this->planet_id,
            'position' => [
                'x' => $this->x,
                'y' => $this->y,
            ],
            'direction' => $this->direction->value, // N, E, S, W
        ];
    }
}
