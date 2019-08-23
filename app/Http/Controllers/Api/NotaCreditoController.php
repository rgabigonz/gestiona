<?php

namespace App\Http\Controllers\Api;

use App\NotaCredito;
use App\NotaCreditoDetalle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class NotaCreditoController extends Controller
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
            $notascreditos = NotaCredito::join('clientes', 'notas_creditos.cliente_id', '=', 'clientes.id')
            ->select('notas_creditos.*', 'clientes.nombre as nombre_cliente')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        } 
        else {
            $notascreditos = NotaCredito::join('clientes', 'notas_creditos.cliente_id', '=', 'clientes.id')
            ->select('notas_creditos.*', 'clientes.nombre as nombre_cliente')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $notascreditos->total(),
                'current_page'  => $notascreditos->currentPage(),
                'per_page'      => $notascreditos->perPage(),    
                'last_page'     => $notascreditos->lastPage(),
                'from'          => $notascreditos->firstItem(),
                'to'            => $notascreditos->lastItem(),
            ],
            'notascreditos' => $notascreditos
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
        $user = auth('api')->user();
        $fecha_nota_credito = \Carbon\Carbon::parse($request->fecha_nota_credito);
        
        $cantidad_items = count($request->items);

        $nota_credito = new NotaCredito();

        $nota_credito->cliente_id = $request->codigo_cliente;
        $nota_credito->user_id = $user->id;
        $nota_credito->total = $request->total_nota_credito;
        $nota_credito->obs = $request->obs;

        $nota_credito->fecha = $fecha_nota_credito->format('Y-m-d');

        $nota_credito->save();

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $nota_credito_item = new NotaCreditoDetalle();

            if (!empty($request->items[$i]['concepto_id']))
                $nota_credito_item->concepto_id = $request->items[$i]['concepto_id'];

            $nota_credito_item->importe = $request->items[$i]['importe'];
            $nota_credito->notaCreditoDetalle()->save($nota_credito_item);
        }
                
        return $nota_credito;
    }

    public function update(Request $request, $id)
    {
        $NotaCredito = NotaCredito::findOrFail($id);

        $NotaCredito->total = $request->total_nota_credito;
        $NotaCredito->obs = $request->obs;

        $NotaCredito->update();
        
        $NotaCreditoDetalle = NotaCreditoDetalle::where('nota_credito_id', $id)->delete();

        $cantidad_items = count($request->items);

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $nota_credito_item = new NotaCreditoDetalle();

            $nota_credito_item->nota_credito_id = $id;

            if (!empty($request->items[$i]['concepto_id']))
                $nota_credito_item->concepto_id = $request->items[$i]['concepto_id'];

            $nota_credito_item->importe = $request->items[$i]['importe'];

            $nota_credito_item->save();
        }
        
        return $request->items;
    }

    public function devuelveNotaCredito(Request $request, $id)
    {
        $datoNotaCredito = NotaCredito::select('notas_creditos.*')
        ->where('notas_creditos.id', '=', $id)->get();        
        
        $datoNotaCreditoD = NotaCreditoDetalle::leftjoin('conceptos', 'notas_creditos_detalle.concepto_id', '=', 'conceptos.id')
        ->select('notas_creditos_detalle.*', 'conceptos.descripcion as descripcion_concepto')
        ->where('notas_creditos_detalle.nota_credito_id', '=', $id)->get();

        return [
            'datoNotaCredito' => $datoNotaCredito,
            'datoNotaCreditoD' => $datoNotaCreditoD,
        ];
    }    

    public function confirmaNotaCredito(Request $request, $id)
    {
        $NotaCredito = NotaCredito::findOrFail($id);
        $NotaCredito->estado = 'CO';
        $NotaCredito->update();
    }    

    public function anulaNotaCredito(Request $request, $id)
    {
        $NotaCredito = NotaCredito::findOrFail($id);
        $NotaCredito->estado = 'AN';
        $NotaCredito->update();
    }            
}
