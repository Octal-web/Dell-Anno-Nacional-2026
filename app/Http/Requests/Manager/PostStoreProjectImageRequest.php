<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreProjectImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'img' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'detalhes' => 'nullable|string|max:720',
            'acabamentos' => 'nullable|array',
            'acabamentos.*' => 'integer|exists:acabamentos,id',
            'colecoes' => 'nullable|array',
            'colecoes.*' => 'integer|exists:colecoes,id',
            'colecao_id' => 'nullable|integer|exists:colecoes,id',
        ];
    }

    public function messages()
    {
        return [
            'img.image' => 'Por favor, selecione uma imagem válida.',
            'img.mimes' => 'Os formatos de imagem válidos são: JPG, PNG e WEBP.',
            'img.max' => 'Por favor, envie uma imagem menor que 5MB.',
            'detalhes.max' => 'Os detalhes devem ter no máximo 720 caracteres.',
            'acabamentos.*.exists' => 'Um dos acabamentos selecionados é inválido.',
            'colecoes.*.exists' => 'Uma das coleções selecionadas é inválida.',
        ];
    }
}
