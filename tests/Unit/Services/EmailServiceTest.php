<?php

namespace Tests\Unit\Services;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Services\EmailService;
use App\Mail\PedidoCriado;
use App\Mail\PedidoAtualizado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EmailServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function envia_email_quando_pedido_criado()
    {
        Mail::fake();

        $cliente = Cliente::factory()->create(['email' => 'teste@pastelaria.com']);
        $pedido = Pedido::factory()->create(['cliente_id' => $cliente->id]);

        // dispara e-mail
        app(EmailService::class)->pedidoCriado($pedido);

        // assert direto pela classe do Mailable
        Mail::assertSent(PedidoCriado::class, function ($mail) use ($cliente) {
            return $mail->hasTo($cliente->email);
        });
    }

    #[Test]
    public function nao_envia_email_se_cliente_nao_tiver_email()
    {
        Mail::fake();

        // cria cliente em memória sem gravar no banco
        $cliente = Cliente::factory()->make(['email' => null]);
        $pedido = Pedido::factory()->make(['cliente_id' => 1]); // id fictício
        $pedido->cliente = $cliente; // sobrescreve cliente

        app(EmailService::class)->pedidoCriado($pedido);

        Mail::assertNothingSent();
    }

    #[Test]
    public function envia_email_quando_pedido_atualizado()
    {
        Mail::fake();

        $cliente = Cliente::factory()->create(['email' => 'teste@pastelaria.com']);
        $pedido = Pedido::factory()->create(['cliente_id' => $cliente->id]);

        app(EmailService::class)->pedidoAtualizado($pedido);

        Mail::assertSent(PedidoAtualizado::class, function ($mail) use ($cliente) {
            return $mail->hasTo($cliente->email);
        });
    }
}
