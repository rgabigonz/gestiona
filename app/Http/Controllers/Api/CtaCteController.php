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

        if ($sCliente) {
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

            $ctacte_clientes = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
            ->select('clientes.id', 'clientes.nombre')
            ->where('notas_pedidos.cliente_id', '=', $sCliente)
            ->where('notas_pedidos.estado', '=', 'CO')
            ->orderBy('notas_pedidos.created_at', 'asc')->distinct()->get();
        } else {
            $ctacte_recibos = Recibo::join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
            ->join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
            ->select('recibos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
            ->where('recibos.estado', '=', 'CO')
            ->orderBy('created_at', 'asc')->get();

            $ctacte_notas_venta = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
            ->select('notas_pedidos.*', 'clientes.nombre as nombre_cliente')
            ->where('notas_pedidos.estado', '=', 'CO')
            ->orderBy('created_at', 'asc')->get();

            $ctacte_clientes = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
            ->select('clientes.id', 'clientes.nombre')
            ->where('notas_pedidos.estado', '=', 'CO')
            ->orderBy('notas_pedidos.created_at', 'asc')->distinct()->get();            
        }

        return [
            'ctacte_recibos' => $ctacte_recibos,
            'ctacte_notas_venta' => $ctacte_notas_venta,
            'ctacte_clientes' => $ctacte_clientes
        ];                    
    } 
}
