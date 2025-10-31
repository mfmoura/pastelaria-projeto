<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriarProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'foto' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome do produto',
            'preco.required' => 'Informe o preço',
            'preco.numeric' => 'Preço inválido',
            'foto.image' => 'Arquivo deve ser uma imagem',
            'foto.max' => 'Imagem muito grande',
        ];
    }
}
