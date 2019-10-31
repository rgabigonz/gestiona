<?php

namespace App\Http\Controllers\Api;

use App\Recibo;
use App\NotaPedido;
use App\NotaDebito;
use App\NotaCredito;
use App\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CtaCteController extends Controller
{
    public function devuelveCtaCte(Request $request)
    {
        $sCliente = $request->cliente;
        $sFechaD = $request->fechaD;
        $sFechaH = $request->fechaH;
        $sUsaFecha = $request->usaFecha;

        if ($sCliente) {
            if ($sUsaFecha == 0) {
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

                $ctacte_notas_debito = NotaDebito::join('clientes', 'notas_debitos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'notas_debitos.sucursal_id', '=', 'sucursales.id')
                ->select('notas_debitos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('notas_debitos.cliente_id', '=', $sCliente)
                ->where('notas_debitos.estado', '=', 'CO')
                ->orderBy('created_at', 'asc')->get();                

                $ctacte_notas_credito = NotaCredito::join('clientes', 'notas_creditos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'notas_creditos.sucursal_id', '=', 'sucursales.id')
                ->select('notas_creditos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('notas_creditos.cliente_id', '=', $sCliente)
                ->where('notas_creditos.estado', '=', 'CO')
                ->orderBy('created_at', 'asc')->get();                

                $ctacte_clientes_NP = Cliente::join('notas_pedidos', 'clientes.id', '=', 'notas_pedidos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('clientes.id', '=', $sCliente)
                ->where('notas_pedidos.estado', '=', 'CO');

                $ctacte_clientes_ND = Cliente::join('notas_debitos', 'clientes.id', '=', 'notas_debitos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('clientes.id', '=', $sCliente)
                ->where('notas_debitos.estado', '=', 'CO');

                $ctacte_clientes_NC = Cliente::join('notas_creditos', 'clientes.id', '=', 'notas_creditos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('clientes.id', '=', $sCliente)
                ->where('notas_creditos.estado', '=', 'CO');

                $ctacte_clientes = Cliente::join('recibos', 'clientes.id', '=', 'recibos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('clientes.id', '=', $sCliente)
                ->where('recibos.estado', '=', 'CO')
                ->union($ctacte_clientes_NP)
                ->union($ctacte_clientes_ND)
                ->union($ctacte_clientes_NC)->distinct()->get();

            } else {
                $ctacte_recibos = Recibo::join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
                ->select('recibos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('recibos.cliente_id', '=', $sCliente)
                ->where('recibos.estado', '=', 'CO')
                ->whereDate('fecha', '>=', $sFechaD)
                ->whereDate('fecha', '<=', $sFechaH)
                ->orderBy('created_at', 'asc')->get();

                $ctacte_notas_venta = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
                ->select('notas_pedidos.*', 'clientes.nombre as nombre_cliente')
                ->where('notas_pedidos.cliente_id', '=', $sCliente)
                ->where('notas_pedidos.estado', '=', 'CO')
                ->whereDate('fecha', '>=', $sFechaD)
                ->whereDate('fecha', '<=', $sFechaH)
                ->orderBy('created_at', 'asc')->get();

                $ctacte_notas_debito = NotaDebito::join('clientes', 'notas_debitos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'notas_debitos.sucursal_id', '=', 'sucursales.id')
                ->select('notas_debitos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('notas_debitos.cliente_id', '=', $sCliente)
                ->where('notas_debitos.estado', '=', 'CO')
                ->whereDate('fecha', '>=', $sFechaD)
                ->whereDate('fecha', '<=', $sFechaH)
                ->orderBy('created_at', 'asc')->get();                

                $ctacte_notas_credito = NotaCredito::join('clientes', 'notas_creditos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'notas_creditos.sucursal_id', '=', 'sucursales.id')
                ->select('notas_creditos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('notas_creditos.cliente_id', '=', $sCliente)
                ->where('notas_creditos.estado', '=', 'CO')
                ->whereDate('fecha', '>=', $sFechaD)
                ->whereDate('fecha', '<=', $sFechaH)
                ->orderBy('created_at', 'asc')->get(); 

                $ctacte_clientes_NP = Cliente::join('notas_pedidos', 'clientes.id', '=', 'notas_pedidos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('clientes.id', '=', $sCliente)
                ->where('notas_pedidos.estado', '=', 'CO')
                ->whereDate('notas_pedidos.fecha', '>=', $sFechaD)
                ->whereDate('notas_pedidos.fecha', '<=', $sFechaH);

                $ctacte_clientes_ND = Cliente::join('notas_debitos', 'clientes.id', '=', 'notas_debitos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('clientes.id', '=', $sCliente)
                ->where('notas_debitos.estado', '=', 'CO')
                ->whereDate('notas_debitos.fecha', '>=', $sFechaD)
                ->whereDate('notas_debitos.fecha', '<=', $sFechaH);

                $ctacte_clientes_NC = Cliente::join('notas_creditos', 'clientes.id', '=', 'notas_creditos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('clientes.id', '=', $sCliente)
                ->where('notas_creditos.estado', '=', 'CO')
                ->whereDate('notas_creditos.fecha', '>=', $sFechaD)
                ->whereDate('notas_creditos.fecha', '<=', $sFechaH);

                $ctacte_clientes = Cliente::join('recibos', 'clientes.id', '=', 'recibos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('recibos.cliente_id', '=', $sCliente)
                ->where('recibos.estado', '=', 'CO')
                ->whereDate('recibos.fecha', '>=', $sFechaD)
                ->whereDate('recibos.fecha', '<=', $sFechaH)
                ->union($ctacte_clientes_NP)
                ->union($ctacte_clientes_ND)
                ->union($ctacte_clientes_NC)->distinct()->get();
            }
        } else {
            if ($sUsaFecha == 0) {
                $ctacte_recibos = Recibo::join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
                ->select('recibos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('recibos.estado', '=', 'CO')
                ->orderBy('created_at', 'asc')->get();

                $ctacte_notas_venta = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
                ->select('notas_pedidos.*', 'clientes.nombre as nombre_cliente')
                ->where('notas_pedidos.estado', '=', 'CO')
                ->orderBy('created_at', 'asc')->get();

                $ctacte_notas_debito = NotaDebito::join('clientes', 'notas_debitos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'notas_debitos.sucursal_id', '=', 'sucursales.id')
                ->select('notas_debitos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('notas_debitos.estado', '=', 'CO')
                ->orderBy('created_at', 'asc')->get();

                $ctacte_notas_credito = NotaCredito::join('clientes', 'notas_creditos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'notas_creditos.sucursal_id', '=', 'sucursales.id')
                ->select('notas_creditos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('notas_creditos.estado', '=', 'CO')
                ->orderBy('created_at', 'asc')->get();

                $ctacte_clientes_NP = Cliente::join('notas_pedidos', 'clientes.id', '=', 'notas_pedidos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('notas_pedidos.estado', '=', 'CO');

                $ctacte_clientes_ND = Cliente::join('notas_debitos', 'clientes.id', '=', 'notas_debitos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('notas_debitos.estado', '=', 'CO');

                $ctacte_clientes_NC = Cliente::join('notas_creditos', 'clientes.id', '=', 'notas_creditos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('notas_creditos.estado', '=', 'CO');

                $ctacte_clientes = Cliente::join('recibos', 'clientes.id', '=', 'recibos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('recibos.estado', '=', 'CO')
                ->union($ctacte_clientes_NP)
                ->union($ctacte_clientes_ND)
                ->union($ctacte_clientes_NC)->distinct()->get();
            } else {
                $ctacte_recibos = Recibo::join('clientes', 'recibos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'recibos.sucursal_id', '=', 'sucursales.id')
                ->select('recibos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('recibos.estado', '=', 'CO')
                ->whereDate('fecha', '>=', $sFechaD)
                ->whereDate('fecha', '<=', $sFechaH)
                ->orderBy('created_at', 'asc')->get();

                $ctacte_notas_venta = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
                ->select('notas_pedidos.*', 'clientes.nombre as nombre_cliente')
                ->where('notas_pedidos.estado', '=', 'CO')
                ->whereDate('fecha', '>=', $sFechaD)
                ->whereDate('fecha', '<=', $sFechaH)
                ->orderBy('created_at', 'asc')->get();

                $ctacte_notas_debito = NotaDebito::join('clientes', 'notas_debitos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'notas_debitos.sucursal_id', '=', 'sucursales.id')
                ->select('notas_debitos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('notas_debitos.estado', '=', 'CO')
                ->whereDate('fecha', '>=', $sFechaD)
                ->whereDate('fecha', '<=', $sFechaH)
                ->orderBy('created_at', 'asc')->get();

                $ctacte_notas_credito = NotaCredito::join('clientes', 'notas_creditos.cliente_id', '=', 'clientes.id')
                ->join('sucursales', 'notas_creditos.sucursal_id', '=', 'sucursales.id')
                ->select('notas_creditos.*', 'clientes.nombre as nombre_cliente', 'sucursales.punto_venta as punto_venta')
                ->where('notas_creditos.estado', '=', 'CO')
                ->whereDate('fecha', '>=', $sFechaD)
                ->whereDate('fecha', '<=', $sFechaH)
                ->orderBy('created_at', 'asc')->get();

                $ctacte_clientes_NP = Cliente::join('notas_pedidos', 'clientes.id', '=', 'notas_pedidos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('notas_pedidos.estado', '=', 'CO')
                ->whereDate('notas_pedidos.fecha', '>=', $sFechaD)
                ->whereDate('notas_pedidos.fecha', '<=', $sFechaH);

                $ctacte_clientes_ND = Cliente::join('notas_debitos', 'clientes.id', '=', 'notas_debitos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('notas_debitos.estado', '=', 'CO')
                ->whereDate('notas_debitos.fecha', '>=', $sFechaD)
                ->whereDate('notas_debitos.fecha', '<=', $sFechaH);

                $ctacte_clientes_NC = Cliente::join('notas_creditos', 'clientes.id', '=', 'notas_creditos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('notas_creditos.estado', '=', 'CO')
                ->whereDate('notas_creditos.fecha', '>=', $sFechaD)
                ->whereDate('notas_creditos.fecha', '<=', $sFechaH);

                $ctacte_clientes = Cliente::join('recibos', 'clientes.id', '=', 'recibos.cliente_id')
                ->select('clientes.id', 'clientes.nombre')
                ->where('recibos.estado', '=', 'CO')
                ->whereDate('recibos.fecha', '>=', $sFechaD)
                ->whereDate('recibos.fecha', '<=', $sFechaH)
                ->union($ctacte_clientes_NP)
                ->union($ctacte_clientes_ND)
                ->union($ctacte_clientes_NC)->distinct()->get();
            }
        }

        return [
            'ctacte_recibos' => $ctacte_recibos,
            'ctacte_notas_venta' => $ctacte_notas_venta,
            'ctacte_notas_debito' => $ctacte_notas_debito,
            'ctacte_notas_credito' => $ctacte_notas_credito,
            'ctacte_clientes' => $ctacte_clientes
        ];                    
    } 
}
