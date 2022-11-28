<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class DriverResource extends JsonResource
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
            'type' => $this->user->type,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => Hash::make($this->user->password),
            'photo_url' => $this->user->photo_url,
            'license_plate' => $this->license_plate,
            'phone_number' => $this->phone_number,
        ];
    }
}
