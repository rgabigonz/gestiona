<?php

namespace App\Http\Controllers\Api;

use App\Banco;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BancoController extends Controller
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
            $bancos = Banco::orderBy('nombre', 'asc')->paginate(15);
        } 
        else {
            $bancos = Banco::where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy('nombre', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $bancos->total(),
                'current_page'  => $bancos->currentPage(),
                'per_page'      => $bancos->perPage(),
                'last_page'     => $bancos->lastPage(),
                'from'          => $bancos->firstItem(),
                'to'            => $bancos->lastItem(),
            ],
            'bancos' => $bancos
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
            'nombre' => 'required|string'
        ], [
            'nombre.required' => 'El nombre es requerido'
        ]);

        return Banco::create([
            'nombre' => $request['nombre']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banco  $banco
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $banco = Banco::findOrFail($id);

        $this->validate($request, [
            'nombre' => 'required|string'
        ], [
            'nombre.required' => 'El nombre es requerido'
        ]);

        $banco->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $banco = Banco::findOrFail($id);
        $banco->estado = 'D';
        $banco->update();
    }

    public function activar(Request $request, $id)
    {
        $banco = Banco::findOrFail($id);
        $banco->estado = 'A';
        $banco->update();
    }

    public function cargaBancos()
    {
        $bancos = Banco::orderBy('nombre', 'asc')->where('estado', '=', 'A')->get();
        return [
            'bancos' => $bancos
        ];
    }         
}
