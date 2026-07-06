<?php

namespace App\Http\Requests\Manager;

use App\Models\Mostra;
use Illuminate\Foundation\Http\FormRequest;

class PostMostraRequest extends FormRequest
{
    private const TIPOS = [
        'casacor',
        'casas-conceito',
        'outras-mostras',
    ];

    private const TIPOS_FIXOS = [
        'casacor',
        'casas-conceito',
    ];

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
        $action = inertia()->getShared('action');

        $criando = $action === 'novo';
        $atualizando = $action === 'atualizar';

        $tipo = $this->input('tipo');

        if ($atualizando) {
            $mostra = Mostra::query()
                ->where([
                    'excluido' => null,
                    'id' => $this->route('id')
                ])
                ->first();

            $tipo = $mostra ? $mostra->slug : $tipo;
        }

        $tipoFixo = in_array($tipo, self::TIPOS_FIXOS, true);

        $criandoMostraNova = $criando && !$tipoFixo;
        $precisaCamposIdioma = $criandoMostraNova || $atualizando;

        return [
            'tipo' => $criando ? 'required|in:' . implode(',', self::TIPOS) : 'nullable|in:' . implode(',', self::TIPOS),

            'ano' => $tipoFixo && $atualizando ? 'nullable|integer|digits:4' : 'required|integer|digits:4',

            'nome' => $precisaCamposIdioma ? 'required|string|max:72' : 'nullable|string|max:72',
            'descricao' => $precisaCamposIdioma ? 'required|string' : 'nullable|string',
            'titulo_pagina' => $precisaCamposIdioma ? 'required|string|max:72' : 'nullable|string|max:72',
            'descricao_pagina' => $precisaCamposIdioma ? 'required|string' : 'nullable|string',

            'img' => $criandoMostraNova
                ? 'required|image|mimes:jpg,png|max:2048'
                : 'nullable|image|mimes:jpg,png|max:2048',
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
            'tipo.required' => 'Tipo inválido.',
            'tipo.in' => 'Tipo inválido.',

            'ano.required' => 'Por favor, informe o ano.',
            'ano.integer' => 'Por favor, informe um ano válido.',
            'ano.digits' => 'Por favor, informe o ano com 4 dígitos.',

            'nome.required' => 'Por favor, informe o nome.',
            'nome.max' => 'Por favor, informe um nome com até 72 caracteres.',

            'descricao.required' => 'Por favor, informe a descrição.',

            'titulo_pagina.required' => 'Por favor, informe o título da página.',
            'titulo_pagina.max' => 'Por favor, informe o título da página com até 72 caracteres.',

            'descricao_pagina.required' => 'Por favor, informe a descrição da página.',

            'img.required' => 'Por favor, selecione uma imagem.',
            'img.image' => 'Por favor, selecione uma imagem válida.',
            'img.mimes' => 'Os formatos de imagem válidos são: JPG e PNG.',
            'img.max' => 'Por favor, envie um arquivo menor que 2MB.',
        ];
    }
}