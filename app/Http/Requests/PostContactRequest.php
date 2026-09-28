<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
    public function rules(): array
    {
        return [
            'nome' => 'required|string|min:3|max:255',
            'email' => 'required|email:rfc,dns|max:255',
            'telefone' => 'required|celular_com_ddd',
            'ocupacao' => 'nullable|string|max:255',
            'estado_id' => 'required|integer|exists:estados,id',
            'cidade_id' => [
                'required',
                'integer',
                Rule::exists('cidades', 'id')->where('estado_id', $this->input('estado_id')),
            ],
            'mensagem' => 'required|string',
            'politica' => 'required|accepted',
            'origem' => 'nullable|string|max:255',
            'campanha' => 'nullable|string|max:255',
            'grupo' => 'nullable|string|max:255',
            'anuncio' => 'nullable|string|max:255',
            'termo' => 'nullable|string|max:255',
            'entrada' => 'nullable|date_format:Y-m-d H:i:s',
            'posicao_formulario' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'Por favor, insira seu nome.',
            'nome.string' => 'O nome informado é inválido.',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'email.required' => 'Por favor, insira seu e-mail.',
            'email.email' => 'Por favor, insira um e-mail válido.',
            'email.max' => 'O e-mail deve ter no máximo 255 caracteres.',
            'telefone.required' => 'Por favor, insira seu telefone.',
            'telefone.celular_com_ddd' => 'Por favor, informe um telefone válido.',
            'ocupacao.string' => 'O cargo informado é inválido.',
            'ocupacao.max' => 'O cargo deve ter no máximo 255 caracteres.',
            'estado_id.required' => 'Por favor, informe o seu estado.',
            'estado_id.integer' => 'Selecione um estado válido.',
            'estado_id.exists' => 'Selecione um estado válido.',
            'cidade_id.required' => 'Por favor, informe a sua cidade.',
            'cidade_id.integer' => 'Selecione uma cidade válida.',
            'cidade_id.exists' => 'Selecione uma cidade do estado informado.',
            'mensagem.required'  => 'Por favor, informe a sua mensagem.',
            'mensagem.string' => 'A descrição do projeto ideal é inválida.',
            'politica.required' => 'Para continuar, você deve concordar com a LGPD.',
            'politica.accepted' => 'Para continuar, você deve concordar com a LGPD.',
            'origem.string' => 'Origem inválida.',
            'origem.max' => 'Origem deve ter no máximo 255 caracteres.',
            'campanha.string' => 'Campanha inválida.',
            'campanha.max' => 'Campanha deve ter no máximo 255 caracteres.',
            'grupo.string' => 'Grupo inválido.',
            'grupo.max' => 'Grupo deve ter no máximo 255 caracteres.',
            'anuncio.string' => 'Anúncio inválido.',
            'anuncio.max' => 'Anúncio deve ter no máximo 255 caracteres.',
            'termo.string' => 'Termo inválido.',
            'termo.max' => 'Termo deve ter no máximo 255 caracteres.',
            'entrada.date_format' => 'A data de entrada é inválida.',
            'posicao_formulario.string' => 'A posição do formulário é inválida.',
            'posicao_formulario.max' => 'A posição do formulário deve ter no máximo 255 caracteres.',
        ];
    }
}
