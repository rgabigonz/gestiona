<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuracion';

    protected $fillable = [
        'vendedor_gestion_id', 'sucursal_id', 'deposito_id'
    ];
}
