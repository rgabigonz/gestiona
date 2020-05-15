<?php

namespace App\Http\Controllers\Api;

use App\Recibo;
use App\Configuracion;
use App\ReciboDetalle;
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
            ->orderBy('created_at', 'desc')
            ->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $recibos = Recibo::join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
            ->join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
            ->select('recibos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('created_at', 'desc')
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();
        $fecha_recibo = \Carbon\Carbon::parse($request->fecha_recibo);
        
        $cantidad_items = count($request->items);

        $configuracion = Configuracion::get();
        $numero_recibo = Recibo::where('sucursal_id', $configuracion[0]->sucursal_id)->max('numero_recibo') + 1;

        $recibo = new Recibo();

        $recibo->sucursal_id = $configuracion[0]->sucursal_id;
        $recibo->cliente_id = $request->codigo_cliente;
        $recibo->user_id = $user->id;
        $recibo->total = $request->total_recibo;
        $recibo->total_dolares = $request->total_dolares;
        $recibo->numero_recibo = $numero_recibo;
        $recibo->obs = $request->obs;
        $recibo->referencia_talonario = $request->referencia_talonario;
        $recibo->precio_dolar_manual = $request->precio_dolar_manual;

        $recibo->fecha = $fecha_recibo->format('Y-m-d');

        $recibo->save();

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $recibo_item = new ReciboDetalle();

            if (!empty($request->items[$i]['fecha_cobro_cheque'])) {
                $fecha_cobro_cheque = \Carbon\Carbon::parse($request->items[$i]['fecha_cobro_cheque']);
                $recibo_item->fecha_cobro_cheque = $fecha_cobro_cheque->format('Y-m-d');
            }

            $recibo_item->tipo_pago = $request->items[$i]['tipo_pago'];

            if (!empty($request->items[$i]['numero_cheque']))
                $recibo_item->numero_cheque = $request->items[$i]['numero_cheque'];

            if (!empty($request->items[$i]['banco_id']))
                $recibo_item->banco_id = $request->items[$i]['banco_id'];

            $recibo_item->importe = $request->items[$i]['importe'];
            $recibo->notaReciboDetalle()->save($recibo_item);
        }
                
        return $recibo;
    }

    public function update(Request $request, $id)
    {
        $Recibo = Recibo::findOrFail($id);
        $Recibo->total = $request->total_recibo;
        $Recibo->total_dolares = $request->total_dolares;
        $Recibo->obs = $request->obs;
        $Recibo->referencia_talonario = $request->referencia_talonario;
        $Recibo->precio_dolar_manual = $request->precio_dolar_manual;

        $Recibo->update();
        
        $ReciboDetalle = ReciboDetalle::where('recibo_id', $id)->delete();

        $cantidad_items = count($request->items);

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $recibo_item = new ReciboDetalle();

            $recibo_item->recibo_id = $id;

            if (!empty($request->items[$i]['fecha_cobro_cheque'])) {
                $fecha_cobro_cheque = \Carbon\Carbon::parse($request->items[$i]['fecha_cobro_cheque']);
                $recibo_item->fecha_cobro_cheque = $fecha_cobro_cheque->format('Y-m-d');
            }

            $recibo_item->tipo_pago = $request->items[$i]['tipo_pago'];

            if (!empty($request->items[$i]['numero_cheque']))
                $recibo_item->numero_cheque = $request->items[$i]['numero_cheque'];

            if (!empty($request->items[$i]['banco_id']))
                $recibo_item->banco_id = $request->items[$i]['banco_id'];

            $recibo_item->importe = $request->items[$i]['importe'];
            $recibo_item->save();
        }
        
        return $Recibo;
    }    

    public function devuelveRecibo(Request $request, $id)
    {
        $datoRecibo = Recibo::join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
        ->select('recibos.*', 'sucursales.punto_venta as punto_venta')
        ->where('recibos.id', '=', $id)->get();        
        
        $datoReciboD = ReciboDetalle::leftjoin('bancos', 'recibos_detalles.banco_id', '=', 'bancos.id')
        ->select('recibos_detalles.*', 'bancos.nombre as nombre_banco')
        ->where('recibos_detalles.recibo_id', '=', $id)->get();

        return [
            'datoRecibo' => $datoRecibo,
            'datoReciboD' => $datoReciboD,
        ];
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
