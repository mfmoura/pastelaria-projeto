<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory, SoftDeletes;

    public $fillable = [
        'nome',
        'email',
        'telefone',
        'data_nascimento',
        'endereco',
        'complemento',
        'bairro',
        'cep',
    ];

    const CREATED_AT = 'data_cadastro';
    const UPDATED_AT = 'data_atualizacao';
    const DELETED_AT = 'data_exclusao';

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
