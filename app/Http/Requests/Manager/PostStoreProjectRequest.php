<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'loja_id' => 'nullable|integer|exists:lojas,id',
            'nome' => 'required|string|max:120',
            'creditos' => 'required|string|max:320',
            'conteudo' => 'nullable|string|max:1520',
            'titulo_pagina' => 'required|string|max:120',
            'descricao_pagina' => 'required|string|max:320',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'loja_id.exists' => 'A loja selecionada é inválida.',

            'nome.required' => 'Por favor, informe o nome.',
            'nome.max' => 'O nome deve ter no máximo 120 caracteres.',

            'creditos.required' => 'Por favor, informe os créditos.',
            'creditos.max' => 'Os créditos devem ter no máximo 320 caracteres.',

            'conteudo.max' => 'O conteúdo deve ter no máximo 1520 caracteres.',

            'titulo_pagina.required' => 'Por favor, informe o título da página.',
            'titulo_pagina.max' => 'O título da página deve ter no máximo 120 caracteres.',

            'descricao_pagina.required' => 'Por favor, informe a descrição da página.',
            'descricao_pagina.max' => 'A descrição da página deve ter no máximo 320 caracteres.',
        ];
    }
}
