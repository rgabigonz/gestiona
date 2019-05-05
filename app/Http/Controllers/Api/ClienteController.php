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
            'correo_electronico' => 'required|string|email|max:255|unique:clientes',
            'numero_documento' => 'required'
        ], [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La direccion es requerida',
            'correo_electronico.required' => 'El correo electronico es requerido',
            'correo_electronico.unique' => 'El correo electronico ya esta registrado',
            'numero_documento.required' => 'El numero de documento es requerido'
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
            'correo_electronico' => 'required|string|email|max:255||unique:clientes,correo_electronico,'.$cliente->id,
            'numero_documento' => 'required'
        ], [
            'nombre.required' => 'El nombre es requerido',
            'direccion.required' => 'La direccion es requerida',
            'correo_electronico.required' => 'El correo electronico es requerido',
            'correo_electronico.unique' => 'El correo electronico ya esta registrado',
            'numero_documento.required' => 'El numero de documento es requerido'
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
        $clientes = Cliente::orderBy('nombre', 'asc')->get();
        return [
            'clientes' => $clientes
        ];
    }     
}
