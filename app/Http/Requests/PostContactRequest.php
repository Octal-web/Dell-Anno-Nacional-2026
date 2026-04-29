<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostContactRequest extends FormRequest
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
            'nome' => 'required',
            'email' => 'required|email',
            'telefone' => 'required|celular_com_ddd',
            'estado_id'  => 'required',
            'cidade_id'  => 'required',
            'mensagem' => 'required',
            'politica' => 'required|accepted',
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
            'nome.required' => 'Por favor, insira seu nome.',
            'email.required' => 'Por favor, insira seu e-mail.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'telefone.required' => 'Por favor, insira seu telefone.',
            'telefone.celular_com_ddd' => 'Por favor, informe um telefone válido.',
            'estado_id.required' => 'Por favor, informe o seu estado.',
            'cidade_id.required' => 'Por favor, informe a sua cidade.',
            'mensagem.required'  => 'Por favor, informe a sua mensagem.',
            'politica.required' => 'Para continuar, você deve concordar com a LGPD.',
            'politica.accepted' => 'Para continuar, você deve concordar com a LGPD.',
        ];
    }
}