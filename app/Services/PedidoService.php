<?php

namespace App\Services;

use App\Models\Pedido;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PedidoService
{
    public static function criar(array $infoPedido): Pedido
    {
        $pedido = Pedido::create($infoPedido);

        self::atualizarProdutos($pedido, $infoPedido['produtos']);

        return $pedido;
    }

    public static function ler(int $id): Pedido
    {
        $pedido = Pedido::with('produtos', 'cliente')->findOrFail($id);

        return $pedido;
    }

    public static function atualizar(int $id, array $infoPedido): Pedido
    {
        $pedido = Pedido::findOrFail($id);

        $pedido->update($infoPedido);

        if(!empty($infoPedido['produtos'])){
            self::atualizarProdutos($pedido, $infoPedido['produtos']);
        }

        return $pedido;
    }

    public static function excluir(int $id): Pedido
    {
        $pedido = Pedido::findOrFail($id);
        
        $pedido->delete();

        return $pedido;
    }

    public static function listar(int $pagina, int $registros): LengthAwarePaginator
    {
        $pedidos = Pedido::with('produtos', 'cliente')
                         ->paginate($registros, ['*'], 'page', $pagina);

        return $pedidos;
    }

    private static function atualizarProdutos(Pedido $pedido, array $produtos)
    {
        $pedido->produtos()->sync($produtos);
    }
}
?>
