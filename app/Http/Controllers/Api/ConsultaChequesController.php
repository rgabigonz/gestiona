<?php

namespace App\Http\Controllers\Api;

use App\ReciboDetalle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConsultaChequesController extends Controller
{
    public function index(Request $request)
    {
        $sBuscar = $request->buscar;
        $sCriterio = $request->criterio;

        if(empty($sBuscar)) {
            $cheques = ReciboDetalle::join('recibos', 'recibos_detalles.recibo_id', '=', 'recibos.id')
            ->join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
            ->select('recibos_detalles.*', 'recibos_detalles.importe as importe_cheque', 'clientes.nombre as nombre_cliente')
            ->where('recibos.estado', '=', 'CO')
            ->where('recibos_detalles.tipo_pago', '=', 'CH')
            ->orderBy('fecha_cobro_cheque', 'asc', 'lol')->paginate(15);
        } 
        else {
            $cheques = ReciboDetalle::join('recibos', 'recibos_detalles.recibo_id', '=', 'recibos.id')
            ->join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
            ->select('recibos_detalles.*', 'recibos_detalles.importe as importe_cheque', 'clientes.nombre as nombre_cliente')
            ->where('recibos.estado', '=', 'CO')
            ->where('recibos_detalles.tipo_pago', '=', 'CH')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy('fecha_cobro_cheque', 'asc')->paginate(15);
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

        $cheque->update();
    }    
}
