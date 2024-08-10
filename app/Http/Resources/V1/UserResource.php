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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status ? 'Ativo' : 'Inativo',
            'usergroup' => [
                'usergroup_id' => $this->usergroup->id,
                'usergroup_name' => $this->usergroup->name,
            ], 
        ];
    }
}
