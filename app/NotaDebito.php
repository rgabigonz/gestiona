<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaDebito extends Model
{
    protected $table = 'notas_debitos';

    protected $fillable = [
        'cliente_id', 'user_id', 'estado', 'total', 'fecha', 'obs'
    ];

    public function notaDebitoDetalle()
    {
        return $this->hasMany('App\NotaDebitoDetalle');
    }
}
