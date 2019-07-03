<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = [
        'nombre', 'direccion', 'correo_electronico', 'telefono', 'tipo_documento', 'numero_documento'
    ];

}
