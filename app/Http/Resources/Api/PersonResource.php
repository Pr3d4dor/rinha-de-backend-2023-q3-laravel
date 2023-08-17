<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'apelido' => $this->nickname,
            'nome' => $this->name,
            'nascimento' => $this->date_of_birth->format('Y-m-d'),
            'stack' => $this->stack,
        ];
    }
}
