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
                'id' => $this->usergroup->id,
                'name' => $this->usergroup->name,
                'status' => $this->usergroup->status ? 'Ativo' : 'Inativo',
            ],
            'company' => [
                'id' => $this->company->id,
                'cnpj' => $this->company->cnpj,
                'name' => $this->company->name,
            ],
        ];
    }
}
