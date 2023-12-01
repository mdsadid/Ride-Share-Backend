<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user'          => new UserResource($this->whenLoaded('user')),
            'year'          => $this->year,
            'make'          => $this->make,
            'model'         => $this->model,
            'color'         => $this->color,
            'license_plate' => $this->license_plate,
        ];
    }
}
