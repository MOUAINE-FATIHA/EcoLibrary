<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La protection admin est gérée par le middleware
    }

    public function rules(): array
    {
        return [
            'nom'         => 'required|string|max:255|unique:categories,nom,' . $this->route('category')?->id,
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
            'nom.unique'   => 'Une catégorie avec ce nom existe déjà.',
        ];
    }
}