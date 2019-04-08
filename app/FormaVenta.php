<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaVenta extends Model
{
    protected $table = 'forma_ventas';

    protected $fillable = [
        'descripcion'
    ];    
}
