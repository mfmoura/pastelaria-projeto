<?php

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClienteService
{
    public static function criar(array $infoCliente): Cliente
    {
        $cliente = Cliente::create($infoCliente);

        return $cliente;
    }

    public static function ler(int $id): Cliente
    {
        $cliente = Cliente::findOrFail($id);

        return $cliente;
    }

    public static function atualizar(int $id, array $infoCliente): Cliente
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->update($infoCliente);

        return $cliente;
    }

    public static function excluir(int $id): Cliente
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->delete();

        return $cliente;
    }

    public static function listar(int $pagina, int $registros): LengthAwarePaginator
    {
        $clientes = Cliente::query()
                           ->paginate($registros, ['*'], 'page', $pagina);

        return $clientes;
    }
}

?>
