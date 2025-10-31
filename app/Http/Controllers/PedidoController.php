<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PedidoService;
use App\Http\Requests\CriarPedidoRequest;
use App\Http\Requests\AtualizarPedidoRequest;
use App\Services\EmailService;

class PedidoController extends Controller
{
    public function __construct(protected EmailService $emailService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagina = $request->query('pagina', 1);
        $registros = $request->query('registros', 10);

        $pedidos = PedidoService::listar($pagina, $registros);

        return $pedidos;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CriarPedidoRequest $request)
    {
        $dados = $request->all();

        $pedido = PedidoService::criar($dados);

        $this->emailService->pedidoCriado($pedido);
        
        return $pedido;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $pedido = PedidoService::ler($id);
        
        return $pedido;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AtualizarPedidoRequest $request, int $id)
    {
        $dados = $request->all();

        $pedido = PedidoService::atualizar($id, $dados);

        $this->emailService->pedidoAtualizado($pedido);
        
        return $pedido;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $pedido = PedidoService::excluir($id);
        
        return $pedido;
    }
}
