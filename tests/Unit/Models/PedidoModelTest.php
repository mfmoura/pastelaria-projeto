<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\DB;

class PedidoModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_ser_criado_com_produtos()
    {
        $pedido = Pedido::factory()->create();
        $produto = Produto::factory()->create();

        $pedido->produtos()->attach($produto->id, ['quantidade' => 2, 'valor_unitario' => $produto->preco]);

        $this->assertDatabaseHas('pedido_produto', [
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id,
            'quantidade' => 2,
            'valor_unitario' => $produto->preco
        ]);
    }

    #[Test]
    public function deletar_soft_deleta_pedido_e_pivot()
    {
        $pedido = Pedido::factory()->create();
        $produto = Produto::factory()->create();

        $pedido->produtos()->attach($produto->id, ['quantidade' => 1, 'valor_unitario' => $produto->preco]);

        $pedido->delete();

        $this->assertSoftDeleted($pedido);
        $this->assertDatabaseHas('pedido_produto', [
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id,
        ]);
    }

    #[Test]
    public function restaurar_pedido_restaurar_pivots()
    {
        $pedido = Pedido::factory()->create();
        $produto = Produto::factory()->create();

        $pedido->produtos()->attach($produto->id, ['quantidade' => 1, 'valor_unitario' => $produto->preco]);

        $pedido->delete();
        $pedido->restore();

        $this->assertNull($pedido->data_exclusao);
        $this->assertNotNull(
            DB::table('pedido_produto')
                ->where('pedido_id', $pedido->id)
                ->where('produto_id', $produto->id)
                ->value('data_exclusao')
        );
    }
}
