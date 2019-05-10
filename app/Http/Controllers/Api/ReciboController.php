<?php

namespace App\Http\Controllers\Api;

use App\Recibo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use PDF;

class ReciboController extends Controller
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
            $recibos = Recibo::join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
            ->join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
            ->select('recibos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
            ->orderBy('created_at', 'asc')
            ->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $recibos = Recibo::join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
            ->join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
            ->select('recibos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('created_at', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $recibos->total(),
                'current_page'  => $recibos->currentPage(),
                'per_page'      => $recibos->perPage(),
                'last_page'     => $recibos->lastPage(),
                'from'          => $recibos->firstItem(),
                'to'            => $recibos->lastItem(),
            ],
            'recibos' => $recibos
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recibo  $recibo
     * @return \Illuminate\Http\Response
     */
    public function show(Recibo $recibo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Recibo  $recibo
     * @return \Illuminate\Http\Response
     */
    public function edit(Recibo $recibo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recibo  $recibo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recibo $recibo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recibo  $recibo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recibo $recibo)
    {
        //
    }

    public function confirmaRecibo(Request $request, $id)
    {
        $Recibo = Recibo::findOrFail($id);
        $Recibo->estado = 'CO';
        $Recibo->update();
    }    

    public function anulaRecibo(Request $request, $id)
    {
        $Recibo = Recibo::findOrFail($id);
        $Recibo->estado = 'AN';
        $Recibo->update();
    }    
}
