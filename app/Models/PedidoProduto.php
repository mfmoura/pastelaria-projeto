<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoProduto extends Pivot
{
    use SoftDeletes;

    protected $table = 'pedido_produto';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected static function booted()
    {
        static::creating(function ($pivot) {
            $pivot->data_cadastro = now();
            $pivot->data_atualizacao = now();
        });

        static::updating(function ($pivot) {
            $pivot->data_atualizacao = now();
        });
    }

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'quantidade',
        'valor_unitario',
    ];

    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';
    const DELETED_AT = 'data_exclusao';
}
