<?php

namespace App\Http\Controllers\Api;

use App\CondicionPago;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CondicionPagoController extends Controller
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
            $condiciones_pago = CondicionPago::orderBy('descripcion', 'asc')->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $condiciones_pago = CondicionPago::where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('descripcion', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $condiciones_pago->total(),
                'current_page'  => $condiciones_pago->currentPage(),
                'per_page'      => $condiciones_pago->perPage(),
                'last_page'     => $condiciones_pago->lastPage(),
                'from'          => $condiciones_pago->firstItem(),
                'to'            => $condiciones_pago->lastItem(),
            ],
            'condiciones_pago' => $condiciones_pago
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
            'descripcion' => 'required|string'
        ], [
            'descripcion.required' => 'La descripcion es requerida'
        ]);

        return CondicionPago::create([
            'descripcion' => $request['descripcion']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CondicionPago  $condicionPago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $condicion_pago = CondicionPago::findOrFail($id);

        $this->validate($request, [
            'descripcion' => 'required|string'
        ], [
            'descripcion.required' => 'La descripcion es requerida'
        ]);

        $condicion_pago->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $condicion_pago = CondicionPago::findOrFail($id);
        $condicion_pago->estado = 'D';
        $condicion_pago->update();
    }

    public function activar(Request $request, $id)
    {
        $condicion_pago = CondicionPago::findOrFail($id);
        $condicion_pago->estado = 'A';
        $condicion_pago->update();
    }

    public function cargaCP()
    {
        $condicionesPago = CondicionPago::orderBy('descripcion', 'asc')->get();
        return [
            'condicionesPago' => $condicionesPago
        ];
    }      
}
