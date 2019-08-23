<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaCredito extends Model
{
    protected $table = 'notas_creditos';

    protected $fillable = [
        'cliente_id', 'user_id', 'estado', 'total', 'fecha', 'obs'
    ];

    public function notaCreditoDetalle()
    {
        return $this->hasMany('App\NotaCreditoDetalle');
    }
}
