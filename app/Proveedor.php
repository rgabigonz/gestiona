<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre', 'direccion', 'correo_electronico', 'telefono', 'tipo_documento', 'numero_documento'
    ];

    public function doctype() 
    {
        return $this->belongsTo('App\DocumentType');
    } 
}
