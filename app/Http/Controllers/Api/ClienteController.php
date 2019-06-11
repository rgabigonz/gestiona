<?php

namespace App\Http\Controllers\Api;

use App\Cliente;
use App\TipoDocumento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClienteController extends Controller
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
            $clientes = Cliente::orderBy('nombre', 'asc')->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $clientes = Cliente::where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('nombre', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $clientes->total(),
                'current_page'  => $clientes->currentPage(),
                'per_page'      => $clientes->perPage(),
                'last_page'     => $clientes->lastPage(),
                'from'          => $clientes->firstItem(),
                'to'            => $clientes->lastItem(),
            ],
            'clientes' => $clientes
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
            'numero_documento' => 'required|unique:clientes'
        ], [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La direccion es requerida',
            'numero_documento.required' => 'El numero de documento es requerido',
            'numero_documento.unique' => 'El numero de documento ya esta registrado'
        ]);

        return Cliente::create([
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
     * @param  \App\Cliente  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->validate($request, [
            'nombre' => 'required|string|max:100',
            'direccion' => 'required|string',
            'numero_documento' => 'required|unique:clientes,numero_documento,'.$cliente->id
        ], [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La direccion es requerida',
            'numero_documento.required' => 'El numero de documento es requerido',
            'numero_documento.unique' => 'El numero de documento ya esta registrado'
        ]);

        $cliente->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->estado = 'D';
        $cliente->update();
    }

    public function activar(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->estado = 'A';
        $cliente->update();
    }

    public function devuelveDatosCliente(Request $request, $id)
    {
        $datoCliente = Cliente::findOrFail($id);
        return [
            'datoCliente' => $datoCliente
        ];

    }

    public function cargaClientes()
    {
        $clientes = Cliente::orderBy('nombre', 'asc')->where('estado', '=', 'A')->get();
        return [
            'clientes' => $clientes
        ];
    }
}
