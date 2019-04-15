<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    protected $table = 'ordenes_compras';

    protected $fillable = [
        'proveedor_id', 'user_id', 'deposito_id', 'estado', 'tipo', 'total'
    ];

    public function ordenCompraDetalle()
    {
        return $this->hasMany('App\OrdenCompraDetalle');
    }          
}
