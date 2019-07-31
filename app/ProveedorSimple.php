<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProveedorSimple extends Model
{
    protected $table = 'proveedores_simple';

    protected $fillable = [
        'nombre'
    ];
}
