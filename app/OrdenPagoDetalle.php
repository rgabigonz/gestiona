<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenPagoDetalle extends Model
{
    protected $table = 'ordenes_pagos_detalle';

    protected $fillable = [
        'ordenpago_id', 'tipo_pago', 'importe'
    ];

    public function OrdenPago() 
    {
        return $this->belongsTo('App\OrdenPago');
    }   
}
