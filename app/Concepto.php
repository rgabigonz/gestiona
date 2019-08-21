<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $table = 'conceptos';

    protected $fillable = [
        'descripcion', 'tipo_comprobante', 'estado'
    ];
}
