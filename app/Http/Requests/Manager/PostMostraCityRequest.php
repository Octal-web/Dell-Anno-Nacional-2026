<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostMostraCityRequest extends FormRequest
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
            'nome' => 'required|string|max:120',
            'cidade' => 'required|string|max:120',
            'mostra_ano_id' => 'nullable|integer|exists:mostras_anos,id',

            'descricao' => 'required|string|max:1020',
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
            'nome.required' => 'Por favor, informe o nome.',
            'nome.max' => 'O nome deve ter no máximo 120 caracteres.',

            'cidade.required' => 'Por favor, informe a cidade.',
            'cidade.max' => 'A cidade deve ter no máximo 120 caracteres.',

            'mostra_ano_id.exists' => 'O ano da mostra selecionado é inválido.',
            
            'descricao.required' => 'Por favor, informe a descrição.',
            'descricao.max' => 'A descrição deve ter no máximo 1020 caracteres.',
        ];
    }
}