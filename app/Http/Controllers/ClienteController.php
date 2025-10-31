<?php

namespace App\Http\Controllers;

use App\Services\ClienteService;
use Illuminate\Http\Request;
use App\Http\Requests\CriarClienteRequest;
use App\Http\Requests\AtualizarClienteRequest;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pagina = $request->query('pagina', 1);
        $registros = $request->query('registros', 10);

        $clientes = ClienteService::listar($pagina, $registros);

        return $clientes;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CriarClienteRequest $request)
    {
        $dados = $request->all();

        $cliente = ClienteService::criar($dados);
        
        return $cliente;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cliente = ClienteService::ler($id);
        
        return $cliente;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AtualizarClienteRequest $request, int $id)
    {
        $dados = $request->all();

        $cliente = ClienteService::atualizar($id, $dados);
        
        return $cliente;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $cliente = ClienteService::excluir($id);
        
        return $cliente;
    }
}
