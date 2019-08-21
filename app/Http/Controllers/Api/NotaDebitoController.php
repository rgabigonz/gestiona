<?php

namespace App\Http\Controllers\Api;

use App\NotaDebito;
use App\NotaDebitoDetalle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotaDebitoController extends Controller
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
            $notasdebitos = NotaDebito::join('clientes', 'notas_debitos.cliente_id', '=', 'clientes.id')
            ->select('notas_debitos.*', 'clientes.nombre as nombre_cliente')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        } 
        else {
            $notasdebitos = NotaDebito::join('clientes', 'notas_debitos.cliente_id', '=', 'clientes.id')
            ->select('notas_debitos.*', 'clientes.nombre as nombre_cliente')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $notasdebitos->total(),
                'current_page'  => $notasdebitos->currentPage(),
                'per_page'      => $notasdebitos->perPage(),    
                'last_page'     => $notasdebitos->lastPage(),
                'from'          => $notasdebitos->firstItem(),
                'to'            => $notasdebitos->lastItem(),
            ],
            'notasdebitos' => $notasdebitos
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
     * @param  \App\NotaDebito  $notaDebito
     * @return \Illuminate\Http\Response
     */
    public function show(NotaDebito $notaDebito)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NotaDebito  $notaDebito
     * @return \Illuminate\Http\Response
     */
    public function edit(NotaDebito $notaDebito)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NotaDebito  $notaDebito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NotaDebito $notaDebito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NotaDebito  $notaDebito
     * @return \Illuminate\Http\Response
     */
    public function destroy(NotaDebito $notaDebito)
    {
        //
    }

    public function devuelveNotaDebito(Request $request, $id)
    {
        $datoNotaDebito = NotaDebito::select('notas_debitos.*')
        ->where('notas_debitos.id', '=', $id)->get();        
        
        $datoNotaDebitoD = NotaDebitoDetalle::leftjoin('conceptos', 'notas_debitos_detalle.concepto_id', '=', 'conceptos.id')
        ->select('notas_debitos_detalle.*', 'conceptos.descripcion as descripcion_concepto')
        ->where('notas_debitos_detalle.nota_debito_id', '=', $id)->get();

        return [
            'datoNotaDebito' => $datoNotaDebito,
            'datoNotaDebitoD' => $datoNotaDebitoD,
        ];
    }    
}
