<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenPago extends Model
{
    protected $table = 'ordenes_pagos';

    protected $fillable = [
        'proveedor_id', 'user_id', 'estado', 'total', 'fecha', 'obs', 'sucursal_id'
    ];

    public function notaOrdenCompraDetalle()
    {
        return $this->hasMany('App\OrdenCompraDetalle', 'ordenpago_id');
    }
}
