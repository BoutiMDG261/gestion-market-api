<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*.name' => 'required|unique:produits,name|string|max:255',
            '*.description' => 'nullable|string|max:255',
            '*.price' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            '*.name.required' => 'Le champ nom du produit est obligatoire.',
            '*.name.unique' => 'Le champ nom du produit doit être unique.',
            '*.name.string' => 'Le nom du produit doit être une chaîne de caractères.',
            '*.name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
            '*.description.string' => 'La description doit être une chaîne de caractères.',
            '*.description.max' => 'La description ne peut pas dépasser 255 caractères.',
            '*.price.required' => 'Le champ prix du produit est obligatoire.',
            '*.price.numeric' => 'Le prix du produit doit être un nombre.',
            '*.price.min' => 'Le prix du produit ne peut pas être en dessous de 1 Ariary.',
        ];
    }

    	/**
	 * Utiliser la méthode prepareForValidation pour transformer la requête en un tableau d'objets, même si un seul objet est fourni.
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$input = $this->all();

		if (!isset($input[0])) {
			$input = [$input];
		}

		$this->replace($input);
	}
}
