<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'stk_min', 'stk_max'
    ];

    public function quotesitem()
    {
        return $this->hasMany('App\PresupuestoDetalle');
    }        
}
