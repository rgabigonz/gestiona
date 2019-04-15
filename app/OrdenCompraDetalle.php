<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompraDetalle extends Model
{
    protected $table = 'ordenes_compras_detalle';

    protected $fillable = [
        'orden_compra_id', 'producto_id', 'cantidad', 'precio', 'user_id'
    ];

    public function producto() 
    {
        return $this->belongsTo('App\Producto');
    } 

    public function ordenCompra() 
    {
        return $this->belongsTo('App\OrdenCompra');
    }    
}
