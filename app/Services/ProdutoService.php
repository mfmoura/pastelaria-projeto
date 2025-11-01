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
        // Extrai extensão e remove prefixo
        if (preg_match('/^data:image\/(\w+);base64,/', $imagem, $matches)) {
            $ext = $matches[1]; // 'png', 'jpeg', 'gif', etc.
            $base64_str = substr($imagem, strpos($imagem, ',') + 1); // remove "data:image/...;base64,"
        } else {
            throw new \Exception("Base64 inválido ou sem tipo de imagem.");
        }

        // Decodifica o conteúdo puro
        $imageData = base64_decode($base64_str);

        if ($imageData === false) {
            throw new \Exception("Base64 inválido.");
        }

        $folder = storage_path('app/public/produtos');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $filename = uniqid() . '.' . $ext;
        $filePath = $folder . '/' . $filename;

        file_put_contents($filePath, $imageData);

        return 'produtos/' . $filename;
    }
}

?>
