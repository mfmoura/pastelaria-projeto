<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriarPedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'produtos' => 'required|array|min:1',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.valor_unitario' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required' => 'Informe o cliente',
            'cliente_id.exists' => 'Cliente não encontrado',
            'produtos.required' => 'Informe os produtos',
            'produtos.*.produto_id.required' => 'Produto obrigatório',
            'produtos.*.produto_id.exists' => 'Produto não encontrado',
            'produtos.*.quantidade.required' => 'Quantidade obrigatória',
            'produtos.*.valor_unitario.required' => 'Valor unitário obrigatório',
        ];
    }
}
