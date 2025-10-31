<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Pedido;
use App\Models\Cliente;

class PedidoCriado extends Mailable
{
    use Queueable, SerializesModels;

    public Cliente $cliente;
    public $produtos;
    public int $total;

    /**
     * Create a new message instance.
     */
    public function __construct(public Pedido $pedido)
    {
        $this->cliente = $pedido->cliente;
        $this->produtos = $pedido->produtos;
        $this->total = $pedido->produtos->sum(function($produto) {
            return $produto->preco_unitario * $produto->quantidade;
        });
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pedido Criado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.pedido_criado',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
