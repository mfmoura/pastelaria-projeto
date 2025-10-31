<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Produto;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class ProdutoTest extends TestCase
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
    public function test_listar_produtos_api()
    {
        $user = self::createTestUser();

        Produto::factory()->count(15)->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/produtos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'success',
                    'data' => [
                        'current_page',
                        'data' => [
                            '*' => [
                                "id",
                                "nome",
                                "preco",
                                "foto",
                                "data_cadastro",
                                "data_atualizacao",
                                "data_exclusao"
                            ],
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
    public function test_cria_produto_api_com_foto_base64()
    {
        $user = self::createTestUser();

        $base64Image = $this->gerarImagemBase64();

        $payload = [
            'nome' => 'Pastel de Carne',
            'preco' => 12.50,
            'foto' => $base64Image
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/produtos', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nome' => 'Pastel de Carne',
                     'preco' => 12.50,
                 ]);

        $json = $response->json('data');

        $this->assertFileExists(
            storage_path('app/public/' . str_replace('storage/', '', $json['foto']))
        );

        $this->assertDatabaseHas('produtos', [
            'nome' => 'Pastel de Carne',
        ]);
    }

    #[Test]
    public function test_mostrar_produto_api()
    {
        $user = self::createTestUser();

        $produto = Produto::factory()->create([
            'nome' => 'Produto Teste',
            'preco' => 15
        ]);

        $response = $this->actingAs($user, 'sanctum')->getJson("/api/produtos/{$produto->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nome' => 'Produto Teste',
                     'preco' => 15
                 ]);
    }

    #[Test]
    public function test_update_produto_api()
    {
        $user = self::createTestUser();

        $produto = Produto::factory()->create([
            'nome' => 'Produto Antigo',
            'preco' => 10.0
        ]);

        $base64Image = $this->gerarImagemBase64();

        $payload = [
            'nome' => 'Produto Atualizado',
            'preco' => 15.0,
            'foto' => $base64Image
        ];

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/produtos/{$produto->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nome' => 'Produto Atualizado',
                     'preco' => 15.0,
                 ]);

        $json = $response->json('data');

        $this->assertFileExists(
            storage_path('app/public/' . str_replace('storage/', '', $json['foto']))
        );

        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => 'Produto Atualizado',
        ]);
    }

    #[Test]
    public function test_delete_produto_api()
    {
        $user = self::createTestUser();

        $produto = Produto::factory()->create([
            'nome' => 'Produto a Deletar',
            'preco' => 20.0
        ]);

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/produtos/{$produto->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'success' => true
                 ]);

        $this->assertSoftDeleted('produtos', 
                [
                    'id' => $produto->id
                ], 
                null, 
                'data_exclusao'
            );
    }

    /**
     * Função auxiliar: gera uma imagem base64 fake
     */
    private function gerarImagemBase64(): string
    {
        $img = imagecreatetruecolor(100, 100);
        $bgColor = imagecolorallocate($img, 255, 200, 0); // amarelo
        imagefilledrectangle($img, 0, 0, 100, 100, $bgColor);

        ob_start();
        imagepng($img);
        $imageData = ob_get_clean();
        imagedestroy($img);

        return base64_encode($imageData);
    }
}