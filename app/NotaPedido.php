<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaPedido extends Model
{
    protected $table = 'notas_pedidos';

    protected $fillable = [
        'cliente_id', 'user_id', 'estado', 'total'
    ];

    public function notaPedidoDetalle()
    {
        return $this->hasMany('App\NotaPedidoDetalle');
    }      
}
