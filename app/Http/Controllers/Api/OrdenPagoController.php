<?php

namespace App\Http\Controllers\Api;

use App\OrdenPago;
use App\OrdenPagoDetalle;
use App\Configuracion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class OrdenPagoController extends Controller
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
            $ordenespago = OrdenPago::join('proveedores', 'ordenes_pagos.proveedor_id', '=', 'proveedores.id')
            ->join('sucursales', 'ordenes_pagos.sucursal_id', '=', 'sucursales.id')
            ->select('ordenes_pagos.*', 'proveedores.nombre as nombre_proveedor', 'sucursales.punto_venta as punto_venta')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        } 
        else {
            $ordenespago = OrdenPago::join('proveedores', 'ordenes_pagos.proveedor_id', '=', 'proveedores.id')
            ->join('sucursales', 'ordenes_pagos.sucursal_id', '=', 'sucursales.id')
            ->select('ordenes_pagos.*', 'proveedores.nombre as nombre_proveedor', 'sucursales.punto_venta as punto_venta')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $ordenespago->total(),
                'current_page'  => $ordenespago->currentPage(),
                'per_page'      => $ordenespago->perPage(),
                'last_page'     => $ordenespago->lastPage(),
                'from'          => $ordenespago->firstItem(),
                'to'            => $ordenespago->lastItem(),
            ],
            'ordenespago' => $ordenespago
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
        $fecha_ordenpago = \Carbon\Carbon::parse($request->fecha_ordenpago);
        
        $cantidad_items = count($request->items);

        $configuracion = Configuracion::get();

        $ordenpago = new OrdenPago();

        $ordenpago->sucursal_id = $configuracion[0]->sucursal_id;
        $ordenpago->proveedor_id = $request->codigo_proveedor;
        $ordenpago->user_id = $user->id;

        $ordenpago->total = $request->total_ordenpago;
        $ordenpago->fecha = $fecha_ordenpago->format('Y-m-d');
        $ordenpago->obs = $request->obs;

        $ordenpago->save();

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $ordenpago_item = new OrdenPagoDetalle();

            $ordenpago_item->tipo_pago = $request->items[$i]['tipo_pago'];

            $ordenpago_item->importe = $request->items[$i]['importe'];
            $ordenpago->notaOrdenCompraDetalle()->save($ordenpago_item);
        }
                
        return $ordenpago;
    }

    public function update(Request $request, $id)
    {
        $OrdenPago = OrdenPago::findOrFail($id);
        $OrdenPago->total = $request->total_ordenpago;
        $OrdenPago->obs = $request->obs;

        $OrdenPago->update();
        
        $OrdenPagoDetalle = OrdenPagoDetalle::where('ordenpago_id', $id)->delete();

        $cantidad_items = count($request->items);

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $ordenpago_item = new OrdenPagoDetalle();

            $ordenpago_item->ordenpago_id = $id;
            $ordenpago_item->tipo_pago = $request->items[$i]['tipo_pago'];
            $ordenpago_item->importe = $request->items[$i]['importe'];

            $ordenpago_item->save();
        }
        
        return $OrdenPago;
    }  

    public function devuelveOrdenPago(Request $request, $id)
    {
        $datoOrdenPago = OrdenPago::join('sucursales', 'ordenes_pagos.sucursal_id', '=', 'sucursales.id')
        ->select('ordenes_pagos.*', 'sucursales.punto_venta as punto_venta')
        ->where('ordenes_pagos.id', '=', $id)->get();        
        
        $datoOrdenPagoD = OrdenPagoDetalle::select('ordenes_pagos_detalle.*')
        ->where('ordenes_pagos_detalle.ordenpago_id', '=', $id)->get();

        return [
            'datoOrdenPago' => $datoOrdenPago,
            'datoOrdenPagoD' => $datoOrdenPagoD,
        ];
    } 

    public function confirmaOrdenPago(Request $request, $id)
    {
        $OrdenPago = OrdenPago::findOrFail($id);
        $OrdenPago->estado = 'CO';
        $OrdenPago->update();
    }    

    public function anulaOrdenPago(Request $request, $id)
    {
        $OrdenPago = OrdenPago::findOrFail($id);
        $OrdenPago->estado = 'AN';
        $OrdenPago->update();
    }     
}
