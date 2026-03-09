<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titre'=> $this->titre,
            'auteur'=> $this->auteur,
            'isbn'=> $this->isbn,
            'description'=> $this->description,
            'date_publication' => $this->date_publication?->format('d/m/Y'),
            'categorie' => new CategoryResource($this->whenLoaded('categorie')),
            'categorie_id' => $this->categorie_id,
            'total_exemplaires'=> $this->total_exemplaires,
            'exemplaires_dispo'=> $this->exemplaires_dispo,
            'exemplaires_degrades'  => $this->exemplaires_degrades,
            'nb_consultations' => $this->nb_consultations,
            'disponible' => $this->exemplaires_dispo > 0,
            'cree_le'   => $this->created_at?->format('d/m/Y H:i'),
            'modifie_le'=> $this->updated_at?->format('d/m/Y H:i'),
        ];
    }
}