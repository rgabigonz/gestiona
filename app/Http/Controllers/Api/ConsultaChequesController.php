<?php

namespace App\Http\Controllers\Api;

use App\ReciboDetalle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ConsultaChequesController extends Controller
{
    public function index(Request $request)
    {
        $sBuscar = $request->buscar;
        $sCriterio = $request->criterio;

        if(empty($sBuscar)) {
            $cheques = ReciboDetalle::join('recibos', 'recibos_detalles.recibo_id', '=', 'recibos.id')
            ->join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
            ->leftJoin('cotizaciones', 'recibos_detalles.fecha_cobro_cheque', '=', 'cotizaciones.fecha')
            ->select('recibos_detalles.*', 'recibos_detalles.importe as importe_cheque', 'clientes.nombre as nombre_cliente', 
                     'recibos.referencia_talonario as referencia_talonario', DB::raw('COALESCE(cotizaciones.precio_dolar, 0) as precio_dolar'))
            ->where('recibos.estado', '=', 'CO')
            ->where('recibos_detalles.tipo_pago', '=', 'CH')
            //->orderBy('referencia_talonario', 'desc')->paginate(15);
            ->orderBy(DB::raw('CONVERT(referencia_talonario, SIGNED)'), 'desc')->paginate(15);
        } 
        else {
            $cheques = ReciboDetalle::join('recibos', 'recibos_detalles.recibo_id', '=', 'recibos.id')
            ->join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
            ->leftJoin('cotizaciones', 'recibos_detalles.fecha_cobro_cheque', '=', 'cotizaciones.fecha')
            ->select('recibos_detalles.*', 'recibos_detalles.importe as importe_cheque', 'clientes.nombre as nombre_cliente', 
                     'recibos.referencia_talonario as referencia_talonario', DB::raw('COALESCE(cotizaciones.precio_dolar, 0) as precio_dolar'))
            ->where('recibos.estado', '=', 'CO')
            ->where('recibos_detalles.tipo_pago', '=', 'CH')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy(DB::raw('CONVERT(referencia_talonario, SIGNED)'), 'desc')->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $cheques->total(),
                'current_page'  => $cheques->currentPage(),
                'per_page'      => $cheques->perPage(),
                'last_page'     => $cheques->lastPage(),
                'from'          => $cheques->firstItem(),
                'to'            => $cheques->lastItem(),
            ],
            'cheques' => $cheques
        ];
    }

    public function cobrarCheque(Request $request, $id)
    {
        $cheque = ReciboDetalle::findOrFail($id);
        $cheque->estado_cheque = 'CO';

        if (!empty($request->proveedor_id))
            $cheque->proveedor_id = $request->proveedor_id;

        if (!empty($request->obs))
            $cheque->obs = $request->obs;

        $cheque->update();
    }

    public function dolarCheque(Request $request, $id, $dolar)
    {
        if ($dolar > 0) {
            $cheque = ReciboDetalle::findOrFail($id);
            $cheque->precio_dolar_cheque = $dolar;

            $cheque->update();
        }
    }    
}
