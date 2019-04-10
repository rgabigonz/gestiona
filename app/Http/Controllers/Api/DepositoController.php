<?php

namespace App\Http\Controllers\Api;

use App\Deposito;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepositoController extends Controller
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
            $depositos = Deposito::orderBy('descripcion', 'asc')->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $depositos = Deposito::where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('descripcion', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $depositos->total(),
                'current_page'  => $depositos->currentPage(),
                'per_page'      => $depositos->perPage(),
                'last_page'     => $depositos->lastPage(),
                'from'          => $depositos->firstItem(),
                'to'            => $depositos->lastItem(),
            ],
            'depositos' => $depositos
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
            'descripcion' => 'required|string',
            'direccion' => 'required|string'
        ], [
            'descripcion.required' => 'La descripcion es requerida',
            'direccion.required' => 'La direccion es requerida'
        ]);

        return Deposito::create([
            'descripcion' => $request['descripcion'],
            'direccion' => $request['direccion']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Depositos  $depositos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $deposito = Deposito::findOrFail($id);

        $this->validate($request, [
            'descripcion' => 'required|string',
            'direccion' => 'required|string'
        ], [
            'descripcion.required' => 'La descripcion es requerida',
            'direccion.required' => 'La direccion es requerida'
        ]);

        $deposito->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $deposito = Deposito::findOrFail($id);
        $deposito->estado = 'D';
        $deposito->update();
    }

    public function activar(Request $request, $id)
    {
        $deposito = Deposito::findOrFail($id);
        $deposito->estado = 'A';
        $deposito->update();
    }
}
