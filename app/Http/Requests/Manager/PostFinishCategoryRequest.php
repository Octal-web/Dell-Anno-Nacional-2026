<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostFinishCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['nome' => 'required|string|max:72'];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Por favor, informe o nome.',
            'nome.max' => 'O nome deve ter no máximo 72 caracteres.',
        ];
    }
}
