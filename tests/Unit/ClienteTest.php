<?php

namespace Tests\Unit\Models;

use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_criar_um_cliente()
    {
        $cliente = Cliente::factory()->create([
            'nome' => 'Teste Cliente',
        ]);

        $this->assertDatabaseHas('clientes', [
            'nome' => 'Teste Cliente'
        ]);
    }

    #[Test]
    public function cliente_tem_relacao_com_pedidos()
    {
        $cliente = Cliente::factory()->create();
        $pedido = Pedido::factory()->create(['cliente_id' => $cliente->id]);

        $this->assertTrue($cliente->pedidos->contains($pedido));
    }

    #[Test]
    public function cliente_suporta_soft_deletes()
    {
        $cliente = Cliente::factory()->create();
        $cliente->delete();

        $this->assertSoftDeleted($cliente);
    }
}
