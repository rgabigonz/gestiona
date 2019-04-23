<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CondicionPago extends Model
{
    protected $table = 'condicion_pagos';

    protected $fillable = [
        'descripcion'
    ];   
}
