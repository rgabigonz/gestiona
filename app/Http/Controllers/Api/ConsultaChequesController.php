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
            $cheques = ReciboDetalle::join('bancos', 'recibos_detalles.banco_id', '=', 'bancos.id')
            ->join('recibos', 'recibos_detalles.recibo_id', '=', 'recibos.id')
            ->select('recibos_detalles.*', 'bancos.nombre as nombre_banco_cheque', 'recibos_detalles.importe as importe_cheque')
            ->where('recibos.estado', '=', 'CO')
            ->orderBy('fecha_cobro_cheque', 'asc')->paginate(15);
        } 
        else {
            $cheques = ReciboDetalle::join('bancos', 'recibos_detalles.banco_id', '=', 'bancos.id')
            ->join('recibos', 'recibos_detalles.recibo_id', '=', 'recibos.id')
            ->select('recibos_detalles.*', 'bancos.nombre as nombre_banco_cheque', 'recibos_detalles.importe as importe_cheque')
            ->where('recibos.estado', '=', 'CO')
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
