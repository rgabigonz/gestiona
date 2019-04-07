<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'tipos_documentos';

    protected $fillable = [
        'descripcion'
    ];

    public function clientes()
    {
        return $this->hasMany('App\Cliente');
    }    
}
