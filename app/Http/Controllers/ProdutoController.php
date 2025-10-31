<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProdutoService;
use App\Http\Requests\CriarProdutoRequest;
use App\Http\Requests\AtualizarProdutoRequest;

class ProdutoController extends Controller
{
    /**
     * Lista os produtos, com ou sem paginação
     */
    public function index(Request $request)
    {
        $pagina = $request->query('pagina', 1);
        $registros = $request->query('registros', 10);

        $produtos = ProdutoService::listar($pagina, $registros);

        return $produtos;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CriarProdutoRequest $request)
    {
        $path = ProdutoService::salvarFoto($request->foto);
        
        $dados = array_merge($request->all(), [
            'foto' => $path
        ]);
        
        $produto = ProdutoService::criar($dados);
        
        return $produto;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $produto = ProdutoService::ler($id);
        
        return $produto;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(AtualizarProdutoRequest $request, int $id)
    {
        if($request->filled('foto')){
            $path = ProdutoService::salvarFoto($request->foto);
            
            $dados = array_merge($request->all(), [
                'foto' => $path
            ]);
        }
        else{
            $dados = $request->all();
        }

        $produto = ProdutoService::atualizar($id, $dados);
        
        return $produto;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $produto = ProdutoService::excluir($id);
        
        return $produto;
    }
}
