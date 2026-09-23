<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class PostContractRequest extends FormRequest
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
            'titulo' => 'required|string|max:100',
            'subtitulo' => 'required|string|max:100',
            'texto' => 'required|string|max:500',
            'img' => inertia()->getShared('action') === 'novo'
                ? 'required|image|mimes:png,jpg|max:4096'
                : 'nullable|image|mimes:png,jpg|max:4096',
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
            'titulo.required' => 'Por favor, informe o titulo.',
            'titulo.max' => 'O titulo deve ter no máximo 100 caracteres.',

            'subtitulo.required' => 'Por favor, informe o subtítulo.',
            'subtitulo.max' => 'A subtítulo deve ter no máximo 100 caracteres.',

            'texto.required' => 'Por favor, informe o texto.',
            'texto.max' => 'O texto da subtitulo deve ter no máximo 500 caracteres.',

            'img.required' => 'Por favor, selecione uma imagem.',
            'img.image' => 'Por favor, selecione uma imagem válida.',
            'img.mimes' => 'Os formatos válidos são JPG e PNG.',
            'img.max' => 'Por favor, envie uma imagem menor que 4MB.',
        ];
    }
}
