<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\PedidoProduto;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\DB;

class PedidoProdutoModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_ser_criado_e_softdelete()
    {
        $pedido = Pedido::factory()->create();
        $produto = Produto::factory()->create();

        $pivot = PedidoProduto::create([
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id,
            'quantidade' => 3,
            'valor_unitario' => $produto->preco
        ]);

        $this->assertDatabaseHas('pedido_produto', ['pedido_id' => $pedido->id, 'produto_id' => $produto->id]);

        $pivot->delete();

        $this->assertNotNull(
            DB::table('pedido_produto')
                ->where('pedido_id', $pedido->id)
                ->where('produto_id', $produto->id)
                ->value('data_exclusao')
        );
    }
}
