<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    /** @use HasFactory<\Database\Factories\ProdutoFactory> */
    use HasFactory, SoftDeletes;

    public $fillable = [
        'nome',
        'preco',
        'foto'
    ];

    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';
    const DELETED_AT = 'data_exclusao';

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'pedido_produto')
                    ->using(PedidoProduto::class)
                    ->withPivot(['quantidade', 'valor_unitario'])
                    ->withTimestamps()
                    ->withTrashed();
    }
}
