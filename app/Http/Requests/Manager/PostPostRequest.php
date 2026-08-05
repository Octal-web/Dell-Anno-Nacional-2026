<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostPostRequest extends FormRequest
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
            'titulo' => 'required',
            'previa' => 'required',
            'conteudo' => 'required',
            'publicado' => [
                'nullable',
                Rule::anyOf([
                    'date_format:Y-m-d\TH:i',
                    'date_format:Y-m-d H:i',
                ]),
            ],
            'post_categoria_id' => 'required|exists:posts_categorias,id',
            'img' => inertia()->getShared('action') == 'novo' ? 'required|image|mimes:png,jpg|max:2048' : 'nullable|image|mimes:png,jpg|max:2048',
            'img_banner' => inertia()->getShared('action') == 'novo' ? 'required|image|mimes:png,jpg|max:4096' : 'nullable|image|mimes:png,jpg|max:4096',
            'titulo_pagina' => 'required',
            'descricao_pagina' => 'required',
            'titulo_compartilhamento' => 'required',
            'descricao_compartilhamento' => 'required',
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
            'titulo.required' => 'Por favor, informe o título.',
            'previa.required' => 'Por favor, informe a prévia.',
            'conteudo.required' => 'Por favor, informe o conteúdo.',
            'publicado.date_format' => 'O formato de data é inválido.',
            'img.required' => 'Por favor, selecione uma imagem.',
            'img.image' => 'Por favor, selecione uma imagem válida.',
            'img.mimes' => 'Os formatos de imagem válidos são: JPG e PNG.',
            'img.max' => 'Por favor, envie um arquivo menor que 2MB.',
            'img_banner.required' => 'Por favor, selecione um banner.',
            'img_banner.image' => 'Por favor, selecione um banner válido.',
            'img_banner.mimes' => 'Os formatos de imagem válidos são: JPG e PNG.',
            'img_banner.max' => 'Por favor, envie um arquivo menor que 4MB.',
            'titulo_pagina.required' => 'Por favor, informe o título da página.',
            'descricao_pagina.required' => 'Por favor, informe a descrição da página.',
            'titulo_compartilhamento.required' => 'Por favor, informe o título de compartilhamento.',
            'descricao_compartilhamento.required' => 'Por favor, informe a descrição de compartilhamento.',
            'post_categoria_id.required' => 'Por favor, selecione uma categoria.',
            'post_categoria_id.exists' => 'Por favor, selecione uma categoria válida.',
        ];
    }
}
