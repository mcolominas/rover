<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommandResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'position' => [
                'x' => $this->resource['position']['x'],
                'y' => $this->resource['position']['y'],
            ],
            'direction' => $this->resource['direction'],
            'path' => $this->resource['path'],
        ];
    }
}
