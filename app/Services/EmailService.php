<?php

namespace App\Services;

use App\Models\Pedido;
use App\Mail\PedidoCriado;
use App\Mail\PedidoAtualizado;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Dispara e-mail quando um pedido é criado.
     */
    public function pedidoCriado(Pedido $pedido): void
    {
        $this->enviarEmailPedido($pedido, PedidoCriado::class);
    }

    /**
     * Dispara e-mail quando um pedido é atualizado.
     */
    public function pedidoAtualizado(Pedido $pedido): void
    {
        $this->enviarEmailPedido($pedido, PedidoAtualizado::class);
    }

    /**
     * Método privado que envia o e-mail, se houver e-mail do cliente.
     */
    private function enviarEmailPedido(Pedido $pedido, string $mailableClass): void
    {
        if (!$pedido->cliente || !$pedido->cliente->email) {
            return;
        }

        Mail::to($pedido->cliente->email)->send(new $mailableClass($pedido));
    }
}
