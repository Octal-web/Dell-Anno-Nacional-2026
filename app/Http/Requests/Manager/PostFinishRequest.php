<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostFinishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:72',
            'categoria_id' => 'required|integer|exists:acabamentos_categorias,id',
            'img' => inertia()->getShared('action') === 'novo'
                ? 'required|image|mimes:jpeg,jpg,png,webp|max:4096'
                : 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Por favor, informe o nome.',
            'nome.max' => 'O nome deve ter no máximo 72 caracteres.',
            'categoria_id.required' => 'Por favor, selecione a categoria.',
            'categoria_id.exists' => 'A categoria selecionada não é válida.',
            'img.required' => 'Por favor, selecione uma imagem.',
            'img.image' => 'Por favor, selecione uma imagem válida.',
            'img.mimes' => 'Os formatos válidos são JPG, PNG e WEBP.',
            'img.max' => 'Por favor, envie uma imagem menor que 4 MB.',
        ];
    }
}
