<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom'=> $this->nom,
            'description' => $this->description,
            'nb_livres'=> $this->whenCounted('livres'),
            'livres'=> LivreResource::collection($this->whenLoaded('livres')),
            'cree_le'=> $this->created_at?->format('d/m/Y H:i'),
            'modifie_le'  => $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}