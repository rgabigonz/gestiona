<?php

namespace App\Http\Controllers\Api;

use App\ProveedorSimple;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProveedorSimpleController extends Controller
{
    public function index(Request $request)
    {
        $sBuscar = $request->buscar;
        $sCriterio = $request->criterio;

        if(empty($sBuscar)) {
            $proveedores = ProveedorSimple::orderBy('nombre', 'asc')->paginate(15);
        } 
        else {
            $proveedores = ProveedorSimple::where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy('nombre', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $proveedores->total(),
                'current_page'  => $proveedores->currentPage(),
                'per_page'      => $proveedores->perPage(),
                'last_page'     => $proveedores->lastPage(),
                'from'          => $proveedores->firstItem(),
                'to'            => $proveedores->lastItem(),
            ],
            'proveedores' => $proveedores
        ];

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:100'
        ], [
            'nombre.required' => 'El nombre es requerido'
        ]);

        return ProveedorSimple::create([
            'nombre' => $request['nombre']
        ]);
    }

    public function update(Request $request, $id)
    {
        $proveedor = ProveedorSimple::findOrFail($id);

        $this->validate($request, [
            'nombre' => 'required|string|max:100'
        ], [
            'nombre.required' => 'El nombre es requerido'
        ]);

        $proveedor->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $proveedor = ProveedorSimple::findOrFail($id);
        $proveedor->estado = 'D';
        $proveedor->update();
    }

    public function activar(Request $request, $id)
    {
        $proveedor = ProveedorSimple::findOrFail($id);
        $proveedor->estado = 'A';
        $proveedor->update();
    }

    public function devuelveDatosProveedor(Request $request, $id)
    {
        $datoProveedor = ProveedorSimple::findOrFail($id);
        return [
            'datoProveedor' => $datoProveedor
        ];

    }    

    public function cargaProveedores()
    {
        $proveedores = ProveedorSimple::orderBy('nombre', 'asc')->where('estado', '=', 'A')->get();
        return [
            'proveedores' => $proveedores
        ];
    }     
}
