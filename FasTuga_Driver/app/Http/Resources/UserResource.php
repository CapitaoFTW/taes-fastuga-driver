<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserResource extends JsonResource
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
            'type' => $this->type,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'photo_url' => $this->photo_url,
            'license_plate' => $this->driver ? $this->driver->license_plate : null,
            'phone_number' => $this->driver ? $this->driver->phone_number : null,
        ];
    }
}
