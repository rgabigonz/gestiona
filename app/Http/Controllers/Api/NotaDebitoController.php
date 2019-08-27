<?php

namespace App\Http\Controllers\Api;

use App\NotaDebito;
use App\NotaDebitoDetalle;
use App\Configuracion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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
            ->join('sucursales', 'notas_debitos.sucursal_id', '=', 'sucursales.id')
            ->select('notas_debitos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        } 
        else {
            $notasdebitos = NotaDebito::join('clientes', 'notas_debitos.cliente_id', '=', 'clientes.id')
            ->join('sucursales', 'notas_debitos.sucursal_id', '=', 'sucursales.id')
            ->select('notas_debitos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();
        $fecha_nota_debito = \Carbon\Carbon::parse($request->fecha_nota_debito);
        
        $cantidad_items = count($request->items);

        $configuracion = Configuracion::get();
        $numero_nota_debito = NotaDebito::where('sucursal_id', $configuracion[0]->sucursal_id)->max('numero_nota_debito') + 1;

        $nota_debito = new NotaDebito();

        $nota_debito->numero_nota_debito = $numero_nota_debito;

        $nota_debito->sucursal_id = $configuracion[0]->sucursal_id;
        $nota_debito->cliente_id = $request->codigo_cliente;
        $nota_debito->user_id = $user->id;
        $nota_debito->total = $request->total_nota_debito;
        $nota_debito->obs = $request->obs;

        $nota_debito->fecha = $fecha_nota_debito->format('Y-m-d');

        $nota_debito->save();

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $nota_debito_item = new NotaDebitoDetalle();

            if (!empty($request->items[$i]['concepto_id']))
                $nota_debito_item->concepto_id = $request->items[$i]['concepto_id'];

            $nota_debito_item->importe = $request->items[$i]['importe'];
            $nota_debito->notaDebitoDetalle()->save($nota_debito_item);
        }
                
        return $nota_debito;
    }

    public function update(Request $request, $id)
    {
        $NotaDebito = NotaDebito::findOrFail($id);

        $NotaDebito->total = $request->total_nota_debito;
        $NotaDebito->obs = $request->obs;

        $NotaDebito->update();
        
        $NotaDebitoDetalle = NotaDebitoDetalle::where('nota_debito_id', $id)->delete();

        $cantidad_items = count($request->items);

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $nota_debito_item = new NotaDebitoDetalle();

            $nota_debito_item->nota_debito_id = $id;

            if (!empty($request->items[$i]['concepto_id']))
                $nota_debito_item->concepto_id = $request->items[$i]['concepto_id'];

            $nota_debito_item->importe = $request->items[$i]['importe'];

            $nota_debito_item->save();
        }
        
        return $request->items;
    }

    public function devuelveNotaDebito(Request $request, $id)
    {
        $datoNotaDebito = NotaDebito::join('sucursales', 'notas_debitos.sucursal_id', '=', 'sucursales.id')
        ->select('notas_debitos.*', 'sucursales.punto_venta as punto_venta')
        ->where('notas_debitos.id', '=', $id)->get();        
        
        $datoNotaDebitoD = NotaDebitoDetalle::leftjoin('conceptos', 'notas_debitos_detalle.concepto_id', '=', 'conceptos.id')
        ->select('notas_debitos_detalle.*', 'conceptos.descripcion as descripcion_concepto')
        ->where('notas_debitos_detalle.nota_debito_id', '=', $id)->get();

        return [
            'datoNotaDebito' => $datoNotaDebito,
            'datoNotaDebitoD' => $datoNotaDebitoD,
        ];
    }    

    public function confirmaNotaDebito(Request $request, $id)
    {
        $NotaDebito = NotaDebito::findOrFail($id);
        $NotaDebito->estado = 'CO';
        $NotaDebito->update();
    }    

    public function anulaNotaDebito(Request $request, $id)
    {
        $NotaDebito = NotaDebito::findOrFail($id);
        $NotaDebito->estado = 'AN';
        $NotaDebito->update();
    }        
}
