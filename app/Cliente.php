<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre', 'direccion', 'correo_electronico', 'telefono', 'tipo_documento', 'numero_documento'
    ];

    public function tipodocumento() 
    {
        return $this->belongsTo('App\TipoDocumento');
    }    
}
