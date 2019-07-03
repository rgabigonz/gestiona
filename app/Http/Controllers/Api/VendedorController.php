<?php

namespace App\Http\Controllers\Api;

use App\Vendedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendedorController extends Controller
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
            $vendedores = Vendedor::orderBy('nombre', 'asc')->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $vendedores = Vendedor::where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('nombre', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $vendedores->total(),
                'current_page'  => $vendedores->currentPage(),
                'per_page'      => $vendedores->perPage(),
                'last_page'     => $vendedores->lastPage(),
                'from'          => $vendedores->firstItem(),
                'to'            => $vendedores->lastItem(),
            ],
            'vendedores' => $vendedores
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
            'correo_electronico' => 'required|string|email|max:255|unique:vendedores'
        ], [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La direccion es requerida',
            'correo_electronico.required' => 'El correo electronico es requerido',
            'correo_electronico.unique' => 'El correo electronico ya esta registrado'
        ]);

        return Vendedor::create([
            'nombre' => $request['nombre'],
            'direccion' => $request['direccion'],
            'correo_electronico' => $request['correo_electronico'],
            'telefono' => $request['telefono'],
            'tipo_documento' => $request['tipo_documento'],
            'numero_documento' => $request['numero_documento']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vendedor = Vendedor::findOrFail($id);

        $this->validate($request, [
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string',
            'correo_electronico' => 'required|string|email|max:255||unique:vendedores,correo_electronico,'.$vendedor->id
        ], [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La direccion es requerida',
            'correo_electronico.required' => 'El correo electronico es requerido',
            'correo_electronico.unique' => 'El correo electronico ya esta registrado'
        ]);

        $vendedor->update($request->all());
        return $request->all();
    }

    public function desactivar(Request $request, $id)
    {
        $vendedor = Vendedor::findOrFail($id);
        $vendedor->estado = 'D';
        $vendedor->update();
    }

    public function activar(Request $request, $id)
    {
        $vendedor = Vendedor::findOrFail($id);
        $vendedor->estado = 'A';
        $vendedor->update();
    }

    public function cargaVendedores()
    {
        $vendedores = Vendedor::orderBy('nombre', 'asc')->where('estado', '=', 'A')->get();
        $vendedor_gestion_defecto = Vendedor::join('configuracion', 'vendedores.id', '=', 'configuracion.vendedor_gestion_id')
        ->select('vendedores.id')->get();

        return [
            'vendedores' => $vendedores,
            'vendedor_gestion_defecto' => $vendedor_gestion_defecto
        ];
    } 

    public function devuelveDatosVendedor(Request $request, $id)
    {
        $datoVendedor = Vendedor::findOrFail($id);
        return [
            'datoVendedor' => $datoVendedor
        ];

    }    
}
