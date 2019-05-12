<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReciboDetalle extends Model
{
    protected $table = 'recibos_detalles';

    protected $fillable = [
        'recibo_id', 'tipo_pago', 'fecha_cheque', 'numero_cheque', 'banco_id', 'importe'
    ];

    public function Recibo() 
    {
        return $this->belongsTo('App\Recibo');
    }       
}