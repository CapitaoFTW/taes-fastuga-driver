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
            'street_address' => $this->street_address,
            'quantity' => $this->quantity,
            'delivered_by' => $this->delivered_by,
            'driver' => $this->user->name ?? null,
        ];
    }
}
