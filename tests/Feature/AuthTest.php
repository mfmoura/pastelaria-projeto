<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function login_com_credenciais_validas_retorna_token()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'senha123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    "success",
                    "token",
                    "user" => [
                        "id",
                        "name",
                        "email",
                        "email_verified_at",
                        "created_at",
                        "updated_at",
                    ]
                 ]);
    }

    #[Test]
    public function login_com_credenciais_invalidas_retorna_erro()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'senhaerrada',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Credenciais inválidas.',
                 ]);
    }

    #[Test]
    public function rota_user_retorna_usuario_autenticado()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/user');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $user->id,
                         'email' => $user->email,
                     ],
                 ]);
    }

    #[Test]
    public function logout_revoga_token()
    {
        $user = User::factory()->create();

        $token = $user->createToken('teste')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
                         ->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Logout realizado com sucesso.',
                 ]);
    }
}
