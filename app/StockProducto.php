<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockProducto extends Model
{
    protected $table = 'stock_productos';

    protected $fillable = [
        'producto_id', 'deposito_id', 'cantidad'
    ];

    protected $primaryKey = ['producto_id', 'deposito_id'];
    public $incrementing = false;   
}
