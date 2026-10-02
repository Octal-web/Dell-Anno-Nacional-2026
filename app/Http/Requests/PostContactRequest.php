<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Services\ContactService;

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
            'cep' => 'required|formato_cep',
            'expectativa_investimento' => ['required', Rule::in(array_keys(ContactService::EXPECTATIVAS))],
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
            'cep.required' => 'Por favor, informe o seu CEP.',
            'cep.formato_cep' => 'Por favor, informe um CEP válido.',
            'expectativa_investimento.required' => 'Por favor, informe o investimento desejado.',
            'expectativa_investimento.in' => 'Selecione uma expectativa de investimento válida.',
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
