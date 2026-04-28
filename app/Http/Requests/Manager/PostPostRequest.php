<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

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
            'publicado'  => 'nullable|date_format:Y-m-d\TH:i',
            'img' => inertia()->getShared('action') == 'novo' ? 'required|image|mimes:png,jpg|max:2048' : 'nullable|image|mimes:png,jpg|max:2048',
            'titulo_pagina' => 'required',
            'descricao_pagina' => 'required',
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
            'img_banner.max' => 'Por favor, envie um arquivo menor que 2MB.',
            'titulo_pagina.required' => 'Por favor, informe o título da página.',
            'descricao_pagina.required' => 'Por favor, informe a descrição da página.',
        ];
    }
}