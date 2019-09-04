<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientoStock extends Model
{
    protected $table = 'movimientos_stock';

    protected $fillable = [
        'producto_id', 'deposito_id', 'user_id', 'fecha', 'cantidad', 'descripcion', 'tipo', 'tipo_documento', 'documento_id'
    ];   
}
