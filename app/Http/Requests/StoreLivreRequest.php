<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLivreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $livreId = $this->route('livre')?->id;

        return [
            'categorie_id'=> 'required|exists:categories,id',
            'titre'=> 'required|string|max:255',
            'auteur'=> 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:livres,isbn,' . $livreId,
            'description' => 'nullable|string',
            'total_exemplaires'=> 'required|integer|min:1',
            'exemplaires_dispo'=> 'required|integer|min:0|lte:total_exemplaires',
            'exemplaires_degrades'  => 'nullable|integer|min:0|lte:total_exemplaires',
            'date_publication' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'categorie_id.required'=> 'La catégorie est obligatoire.',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'titre.required'=> 'Le titre est obligatoire.',
            'auteur.required'=> 'L\'auteur est obligatoire.',
            'isbn.unique'=> 'Cet ISBN est déjà utilisé.',
            'total_exemplaires.required' => 'Le nombre total d\'exemplaires est obligatoire.',
            'exemplaires_dispo.lte'=> 'Les exemplaires disponibles ne peuvent pas dépasser le total.',
            'exemplaires_degrades.lte'=> 'Les exemplaires dégradés ne peuvent pas dépasser le total.',
        ];
    }
}