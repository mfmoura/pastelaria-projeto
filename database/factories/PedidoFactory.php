<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Pedido;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition()
    {
        return [
            'cliente_id'       => Cliente::factory(),
            'data_cadastro'    => now(),
            'data_atualizacao' => now(),
            'data_exclusao'    => null,
        ];
    }

    /**
     * Cria itens do pedido na pivot pedido_produto
     */
    public function withItens(int $qtdItens = 3)
    {
        return $this->afterCreating(function (Pedido $pedido) use ($qtdItens) {
            // Cria produtos aleatórios
            $produtos = Produto::factory()->count($qtdItens)->create();

            foreach ($produtos as $produto) {
                // Cria os registros pivot usando attach
                $pedido->produtos()->attach($produto->id, [
                    'quantidade'       => rand(1, 5),
                    'valor_unitario'   => $produto->preco,
                    'data_cadastro'    => now(),
                    'data_atualizacao' => now(),
                    'data_exclusao'    => null,
                ]);
            }
        });
    }
}
