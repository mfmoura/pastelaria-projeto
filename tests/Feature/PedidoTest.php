<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Cliente;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class PedidoTest extends TestCase
{
    use RefreshDatabase;
    
    protected function createTestUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'name' => 'Test User',
            'email' => 'admin@example.com',
        ], $overrides));
    }

    #[Test]
    public function listar_pedidos_api()
    {
        $user = self::createTestUser();

        Pedido::factory()->count(15)->withItens(2)->create();

        $pagina = 1;
        $registros = 10;

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/pedidos?pagina={$pagina}&registros={$registros}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'current_page',
                         'data' => [
                             '*' => [
                                 'id',
                                 'cliente_id',
                                 'data_cadastro',
                                 'data_atualizacao',
                                 'data_exclusao',
                                 'produtos' => [
                                    '*' => [
                                        "id",
                                        "nome",
                                        "preco",
                                        "foto",
                                        "data_cadastro",
                                        "data_atualizacao",
                                        "data_exclusao",
                                        "pivot" => [
                                            "pedido_id",
                                            "produto_id",
                                            "quantidade",
                                            "valor_unitario",
                                            "data_cadastro",
                                            "data_atualizacao",
                                        ]
                                    ]
                                 ]
                             ]
                         ],
                         'per_page',
                         'total',
                         'last_page',
                         'next_page_url',
                         'prev_page_url',
                         'first_page_url',
                         'last_page_url',
                         'links'
                     ]
                 ]);

        $json = $response->json('data');
        $this->assertEquals($pagina, $json['current_page']);
        $this->assertCount($registros, $json['data']);
    }

    #[Test]
    public function cria_pedido_api()
    {
        $user = self::createTestUser();

        $cliente = Cliente::factory()->create();
        $produtos = Produto::factory()->count(2)->create();

        $payload = [
            'cliente_id' => $cliente->id,
            'produtos' => $produtos->map(function($produto){
                return [
                    'produto_id'     => $produto->id,
                    'quantidade'     => 2,
                    'valor_unitario' => $produto->preco,
                ];
            })->toArray()
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/pedidos', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'data']);

        $json = $response->json('data');
        $pedidoId = $json['id'];

        $this->assertDatabaseHas('pedidos', ['id' => $pedidoId, 'cliente_id' => $cliente->id]);

        foreach ($produtos as $produto) {
            $this->assertDatabaseHas('pedido_produto', [
                'pedido_id'      => $pedidoId,
                'produto_id'     => $produto->id,
                'quantidade'     => 2,
                'valor_unitario' => $produto->preco,
            ]);
        }
    }

    #[Test]
    public function mostrar_pedido_api()
    {
        $user = self::createTestUser();

        $pedido = Pedido::factory()->withItens(2)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/pedidos/{$pedido->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);

        $json = $response->json('data');
        $this->assertEquals($pedido->id, $json['id']);
        $this->assertEquals($pedido->cliente_id, $json['cliente_id']);
        $this->assertArrayHasKey('produtos', $json);
        $this->assertCount(2, $json['produtos']);
    }

    #[Test]
    public function update_pedido_api()
    {
        $user = self::createTestUser();

        $pedido = Pedido::factory()->withItens(2)->create();
        $novoCliente = Cliente::factory()->create();

        $payload = [
            'cliente_id' => $novoCliente->id,
        ];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/pedidos/{$pedido->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);

        $json = $response->json('data');
        $this->assertEquals($pedido->id, $json['id']);
        $this->assertEquals($novoCliente->id, $json['cliente_id']);

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'cliente_id' => $novoCliente->id]);
    }

    #[Test]
    public function delete_pedido_api()
    {
        $user = self::createTestUser();

        $pedido = Pedido::factory()->withItens(2)->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/pedidos/{$pedido->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertSoftDeleted(
            'pedidos', 
            [
                'id' => $pedido->id
            ],
            null,
            'data_exclusao'
        );

        foreach ($pedido->produtos as $produto) {
            $this->assertSoftDeleted('pedido_produto', 
                [
                    'pedido_id' => $pedido->id,
                    'produto_id'=> $produto->id
                ],
                null,
                'data_exclusao'
            );
        }
    }
}
