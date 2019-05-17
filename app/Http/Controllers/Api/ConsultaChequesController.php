<?php

namespace App\Http\Controllers\Api;

use App\ReciboDetalle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

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
            ->orderBy('fecha_cheque', 'asc')->paginate(15);
        } 
        else {
            $cheques = ReciboDetalle::join('bancos', 'recibos_detalles.banco_id', '=', 'bancos.id')
            ->join('recibos', 'recibos_detalles.recibo_id', '=', 'recibos.id')
            ->select('recibos_detalles.*', 'bancos.nombre as nombre_banco_cheque', 'recibos_detalles.importe as importe_cheque')
            ->where('recibos.estado', '=', 'CO')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy('fecha_cheque', 'asc')->paginate(15);

            /*$cheques = Recibo::join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
            ->join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
            ->select('recibos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('created_at', 'asc')
            ->paginate(15);*/
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
        $fecha_cobro = \Carbon\Carbon::parse($request->fecha_cobro_cheque);

        $cheque = ReciboDetalle::findOrFail($id);
        $cheque->estado_cheque = 'CO';
        $cheque->fecha_cobro_cheque = $fecha_cobro->format('Y-m-d');
        $cheque->update();
    }    
}
