<?php

namespace Tests\Unit\Models;

use App\Models\Produto;
use App\Models\Pedido;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProdutoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_criar_um_produto()
    {
        $produto = Produto::factory()->create([
            'nome' => 'Pastel de Queijo',
            'preco' => 10.50
        ]);

        $this->assertDatabaseHas('produtos', [
            'nome' => 'Pastel de Queijo',
            'preco' => 10.50
        ]);
    }

    #[Test]
    public function produto_tem_relacao_com_pedidos()
    {
        $produto = Produto::factory()->create();
        $pedido = Pedido::factory()->create();

        $pedido->produtos()->attach($produto->id, [
            'quantidade' => 2,
            'valor_unitario' => $produto->preco,
            'data_cadastro' => now(),
            'data_atualizacao' => now()
        ]);

        $this->assertTrue($pedido->produtos->contains($produto));
    }

    #[Test]
    public function produto_suporta_soft_deletes()
    {
        $produto = Produto::factory()->create();
        $produto->delete();

        $this->assertSoftDeleted($produto);
    }
}
