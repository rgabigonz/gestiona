<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaCreditoDetalle extends Model
{
    protected $table = 'notas_creditos_detalle';

    protected $fillable = [
        'nota_credito_id', 'concepto_id', 'importe'
    ];

    public function NotaCredito() 
    {
        return $this->belongsTo('App\NotaCredito');
    }    
}
