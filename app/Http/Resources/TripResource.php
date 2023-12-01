<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'user'             => new UserResource($this->whenLoaded('user')),
            'driver'           => new DriverResource($this->whenLoaded('driver')),
            'is_started'       => $this->is_started,
            'is_completed'     => $this->is_completed,
            'origin'           => $this->origin,
            'destination'      => $this->destination,
            'destination_name' => $this->destination_name,
            'driver_location'  => $this->driver_location,
        ];
    }
}
