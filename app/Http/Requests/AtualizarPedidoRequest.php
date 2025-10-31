<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtualizarPedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => 'sometimes|exists:clientes,id',
            'produtos' => 'sometimes|array|min:1',
            'produtos.*.produto_id' => 'required_with:produtos|exists:produtos,id',
            'produtos.*.quantidade' => 'required_with:produtos|integer|min:1',
            'produtos.*.valor_unitario' => 'required_with:produtos|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.exists' => 'Cliente não encontrado',
            'produtos.*.produto_id.required_with' => 'Produto obrigatório',
            'produtos.*.produto_id.exists' => 'Produto não encontrado',
            'produtos.*.quantidade.required_with' => 'Quantidade obrigatória',
            'produtos.*.valor_unitario.required_with' => 'Valor unitário obrigatório',
        ];
    }
}