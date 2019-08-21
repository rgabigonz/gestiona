<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaDebitoDetalle extends Model
{
    protected $table = 'notas_debitos_detalle';

    protected $fillable = [
        'nota_debito_id', 'concepto_id', 'importe'
    ];

    public function NotaDebito() 
    {
        return $this->belongsTo('App\NotaDebito');
    }       
}
