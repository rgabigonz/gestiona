<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = 'forma_pagos';

    protected $fillable = [
        'descripcion'
    ];    
}
