<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaPedidoDetalle extends Model
{
    protected $table = 'notas_pedidos_detalle';

    protected $fillable = [
        'nota_pedido_id', 'producto_id', 'cantidad', 'precio', 'user_id'
    ];

    public function producto() 
    {
        return $this->belongsTo('App\Producto');
    } 

    public function notaPedido() 
    {
        return $this->belongsTo('App\NotaPedido');
    }    
}
