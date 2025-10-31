<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriarClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'endereco' => 'nullable|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome',
            'email.required' => 'Informe o email',
            'email.email' => 'Email inválido',
            'email.unique' => 'Este email já está cadastrado',
            'telefone.max' => 'Telefone muito longo',
            'data_nascimento.date' => 'Data de nascimento inválida',
            'endereco.max' => 'Endereço muito longo',
            'complemento.max' => 'Complemento muito longo',
            'bairro.max' => 'Bairro muito longo',
            'cep.max' => 'CEP muito longo',
        ];
    }
}
