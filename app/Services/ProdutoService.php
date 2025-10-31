<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProdutoService
{
    public static function criar(array $infoProduto): Produto
    {
        $produto = Produto::create($infoProduto);

        return $produto;
    }

    public static function ler(int $id): Produto
    {
        $produto = Produto::findOrFail($id);

        return $produto;
    }

    public static function atualizar(int $id, array $infoProduto): Produto
    {
        $produto = Produto::findOrFail($id);

        $produto->update($infoProduto);

        return $produto;
    }

    public static function excluir(int $id): Produto
    {
        $produto = Produto::findOrFail($id);

        $produto->delete();

        return $produto;
    }

    public static function listar(int $pagina, int $registros): LengthAwarePaginator
    {
        $produtos = Produto::query()
                           ->paginate($registros, ['*'], 'page', $pagina);

        return $produtos;
    }

    public static function salvarFoto(string $imagem)
    {
        $imageData = base64_decode($imagem);

        if ($imageData === false) {
            throw new \Exception("Base64 inválido.");
        }

        $folder = storage_path('app/public/produtos');
        
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $filename = uniqid() . '.png';
        $filePath = $folder . '/' . $filename;

        file_put_contents($filePath, $imageData);

        return 'storage/produtos/' . $filename;
    } 
}

?>
