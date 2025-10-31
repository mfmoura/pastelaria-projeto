<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Cliente;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class ClienteTest extends TestCase
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
    public function listar_clientes_api()
    {
        Cliente::factory()->count(15)->create();
        
        $user = self::createTestUser();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/clientes?pagina=1&registros=10');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'success',
                    'data' => [
                        'current_page',
                        'data' => [
                            '*' => [
                                'id',
                                'nome',
                                'email',
                                'telefone',
                                'data_nascimento',
                                'endereco',
                                'complemento',
                                'bairro',
                                'cep',
                                'data_cadastro',
                                'data_atualizacao',
                                'data_exclusao',
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

        $this->assertCount(10, $response->json('data.data'));
    }

    #[Test]
    public function cria_cliente_api()
    {
        $user = self::createTestUser();

        $payload = [
            'nome'            => 'João Silva',
            'email'           => 'joao@example.com',
            'telefone'        => '11999998888',
            'data_nascimento' => '1990-05-10',
            'endereco'        => 'Rua A, 123',
            'complemento'     => 'Apto 22',
            'bairro'          => 'Centro',
            'cep'             => '01001-000',
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/clientes', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure(['data' => ['id']]);

        $this->assertDatabaseHas('clientes', [
            'email' => 'joao@example.com',
        ]);
    }

    #[Test]
    public function mostrar_cliente_api()
    {
        $user = self::createTestUser();

        $cliente = Cliente::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/clientes/{$cliente->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $cliente->id,
                     ],
                 ]);
    }

    #[Test]
    public function update_cliente_api()
    {
        $user = self::createTestUser();

        $cliente = Cliente::factory()->create();

        $payload = [
            'nome' => 'Nome Atualizado',
            'email' => 'newemail@example.com'
        ];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/clientes/{$cliente->id}", $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $cliente->id,
                         'nome' => 'Nome Atualizado',
                         'email' => 'newemail@example.com',
                     ],
                 ]);

        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nome' => 'Nome Atualizado'
        ]);
    }

    #[Test]
    public function delete_cliente_api()
    {
        $user = self::createTestUser();

        $cliente = Cliente::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/clientes/{$cliente->id}");

        $response->assertStatus(200);

        // Soft delete
        $this->assertSoftDeleted('clientes', [
                'id' => $cliente->id,
            ],
            null,
            'data_exclusao'
        );
    }
}
