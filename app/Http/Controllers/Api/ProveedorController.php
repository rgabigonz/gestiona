<?php

namespace App\Http\Controllers\Api;

use App\Proveedor;
use App\TipoDocumento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sBuscar = $request->buscar;
        $sCriterio = $request->criterio;

        if(empty($sBuscar)) {
            $proveedores = Proveedor::orderBy('nombre', 'asc')->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $proveedores = Proveedor::where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string',
            'correo_electronico' => 'required|string|email|max:255|unique:proveedores',
            'telefono' => 'required',
            'numero_documento' => 'required',
        ], [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La direccion es requerida',
            'correo_electronico.required' => 'El correo electronico es requerido',
            'correo_electronico.unique' => 'El correo electronico ya esta registrado',
            'telefono.required' => 'El numero de telefono es requerido',
            'numero_documento.required' => 'El numero de documento es requerido'
        ]);

        return Proveedor::create([
            'nombre' => $request['nombre'],
            'direccion' => $request['direccion'],
            'correo_electronico' => $request['correo_electronico'],
            'telefono' => $request['telefono'],
            'tipo_documento' => $request['tipo_documento'],
            'numero_documento' => $request['numero_documento'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $this->validate($request, [
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string',
            'correo_electronico' => 'required|string|email|max:255||unique:proveedores,correo_electronico,'.$proveedor->id,
            'telefono' => 'required',
            'numero_documento' => 'required',
        ], [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La direccion es requerida',
            'correo_electronico.required' => 'El correo electronico es requerido',
            'correo_electronico.unique' => 'El correo electronico ya esta registrado',
            'telefono.required' => 'El numero de telefono es requerido',
            'numero_documento.required' => 'El numero de documento es requerido'
        ]);

        $proveedor->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->estado = 'D';
        $proveedor->update();
    }

    public function activar(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->estado = 'A';
        $proveedor->update();
    }

    public function cargaTD()
    {
        $tiposDocumento = TipoDocumento::orderBy('descripcion', 'asc')->get();
        return [
            'tiposDocumento' => $tiposDocumento
        ];

    }

    public function devuelveDatosProveedor(Request $request, $id)
    {
        $datoProveedor = Proveedor::findOrFail($id);
        return [
            'datoProveedor' => $datoProveedor
        ];

    }    

    public function cargaProveedores()
    {
        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();
        return [
            'proveedores' => $proveedores
        ];
    }     
}
