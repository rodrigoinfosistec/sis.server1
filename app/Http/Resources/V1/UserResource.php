<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => mb_convert_encoding($this->name, 'UTF-8', 'UTF-8'),
            'email' => $this->email,
            'status' => $this->status,
        ];
    }
}
