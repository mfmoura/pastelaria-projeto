<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ClienteModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_ser_criado_e_deletado_soft()
    {
        $cliente = Cliente::factory()->create();

        $this->assertDatabaseHas('clientes', ['id' => $cliente->id, 'data_exclusao' => null]);

        $cliente->delete();
        $this->assertSoftDeleted($cliente);

        $cliente->restore();
        $this->assertDatabaseHas('clientes', ['id' => $cliente->id, 'data_exclusao' => null]);
    }
}
