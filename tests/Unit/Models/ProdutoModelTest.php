<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ProdutoModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pode_ser_criado_e_deletado_soft()
    {
        $produto = Produto::factory()->create();

        $this->assertDatabaseHas('produtos', ['id' => $produto->id, 'data_exclusao' => null]);

        $produto->delete();
        $this->assertSoftDeleted($produto);

        $produto->restore();
        $this->assertDatabaseHas('produtos', ['id' => $produto->id, 'data_exclusao' => null]);
    }
}
