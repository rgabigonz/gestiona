<?php

namespace App\Http\Controllers\Api;

use App\Recibo;
use App\NotaPedido;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CtaCteController extends Controller
{
    public function devuelveCtaCte(Request $request)
    {
        $sCliente = $request->cliente;
        $sFechaD = $request->fechaD;//\Carbon\Carbon::parse($request->fechaD);
        $sFechaH = $request->fechaH;//\Carbon\Carbon::parse($request->fechaH);

        $ctacte_recibos = Recibo::join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
        ->join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
        ->select('recibos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
        ->where('recibos.cliente_id', '=', $sCliente)
        ->where('recibos.estado', '=', 'CO')
        ->orderBy('created_at', 'asc')->get();

        $ctacte_notas_venta = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
        ->select('notas_pedidos.*', 'clientes.nombre as nombre_cliente')
        ->where('notas_pedidos.cliente_id', '=', $sCliente)
        ->where('notas_pedidos.estado', '=', 'CO')
        ->orderBy('created_at', 'asc')->get();        
        
        return [
            'ctacte_recibos' => $ctacte_recibos,
            'ctacte_notas_venta' => $ctacte_notas_venta//,'sFechaD' => $sFechaD->format('Y-m-d')
        ];
    }
}
