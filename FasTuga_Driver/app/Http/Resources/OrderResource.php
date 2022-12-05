<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'status' => $this->status,
            'status_name' => $this->statusName,
            'total_price' => $this->total_price,
            'distance' => $this->distance,
            'quantity' => $this->quantity,
            'driver_id' => $this->driver_id,
            'driver' => $this->user->name ?? null,
            'accepted' => $this->accepted,
        ];
    }
}
