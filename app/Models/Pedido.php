<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Pedido extends Model
{
    /** @use HasFactory<\Database\Factories\PedidoFactory> */
    use HasFactory, SoftDeletes;

    public $fillable = [
        'cliente_id'
    ];

    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';
    const DELETED_AT = 'data_exclusao';

    protected static function booted()
    {
        static::deleting(function ($pedido) {
            if ($pedido->isForceDeleting()) {
                $pedido->produtos()->detach();
            } else {
                DB::table('pedido_produto')
                    ->where('pedido_id', $pedido->id)
                    ->whereNull('data_exclusao')
                    ->update([
                        'data_exclusao'   => now(),
                        'data_atualizacao'=> now(),
                    ]);
            }
        });

        static::restoring(function ($pedido) {

            $pedido->load('produtos'); // ← IMPORTANTE

            $produtosIds = $pedido->produtos->pluck('id')->toArray();

            if (!empty($produtosIds)) {
                $pedido->produtos()->updateExistingPivot(
                    $produtosIds,
                    ['data_exclusao' => null]
                );
            }
        });

    }


    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'pedido_produto')
            ->using(PedidoProduto::class)
            ->withPivot(['quantidade', 'valor_unitario', 'data_cadastro', 'data_atualizacao', 'data_exclusao'])
            ->withTimestamps(['data_cadastro', 'data_atualizacao'])
            ->wherePivotNull('data_exclusao')
            ->withTrashed();
    }
}
