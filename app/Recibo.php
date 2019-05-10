<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'recibos';

    protected $fillable = [
        'cliente_id', 'user_id', 'estado', 'total', 'fecha', 'obs', 'sucursal_id', 'numero_recibo'
    ];

    public function notaReciboDetalle()
    {
        return $this->hasMany('App\ReciboDetalle');
    }
}