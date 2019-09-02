<?php

namespace App\Http\Controllers\Api;

use App\StockProducto;
use App\Producto;
use App\Deposito;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockProductoController extends Controller
{
    public function devuelveStock(Request $request)
    {
        $sDeposito = $request->deposito;
        $sProducto = $request->producto;

        if ($sDeposito) {
            if ($sProducto == 0) {
                $stock_producto = StockProducto::join('productos', 'stock_productos.producto_id', '=', 'productos.id')
                ->select('stock_productos.*', 'productos.nombre as nombre_producto')
                ->where('stock_productos.deposito_id', '=', $sDeposito)
                ->orderBy('created_at', 'asc')->get();

                $stock_depositos = StockProducto::join('depositos', 'depositos.id', '=', 'stock_productos.deposito_id')
                ->select('depositos.id', 'depositos.descripcion')
                ->where('depositos.id', '=', $sDeposito)->distinct()->get();
            } else {
                $stock_producto = StockProducto::join('productos', 'stock_productos.producto_id', '=', 'productos.id')
                ->select('stock_productos.*', 'productos.nombre as nombre_producto')
                ->where('stock_productos.deposito_id', '=', $sDeposito)
                ->where('stock_productos.producto_id', '=', $sProducto)
                ->orderBy('created_at', 'asc')->get();

                $stock_depositos = StockProducto::join('depositos', 'depositos.id', '=', 'stock_productos.deposito_id')
                ->select('depositos.id', 'depositos.descripcion')
                ->where('depositos.id', '=', $sDeposito)->distinct()->get();
            }
        } else {
            if ($sProducto == 0) {
                $stock_producto = StockProducto::join('productos', 'stock_productos.producto_id', '=', 'productos.id')
                ->select('stock_productos.*', 'productos.nombre as nombre_producto')
                ->orderBy('created_at', 'asc')->get();

                $stock_depositos = StockProducto::join('depositos', 'depositos.id', '=', 'stock_productos.deposito_id')
                ->select('depositos.id', 'depositos.descripcion')->distinct()->get();
            } else {
                $stock_producto = StockProducto::join('productos', 'stock_productos.producto_id', '=', 'productos.id')
                ->select('stock_productos.*', 'productos.nombre as nombre_producto')
                ->where('stock_productos.producto_id', '=', $sProducto)
                ->orderBy('created_at', 'asc')->get();

                $stock_depositos = StockProducto::join('depositos', 'depositos.id', '=', 'stock_productos.deposito_id')
                ->select('depositos.id', 'depositos.descripcion')->distinct()->get();
            }
        }

        return [
            'stock_depositos' => $stock_depositos,
            'stock_producto' => $stock_producto
        ];                    
    }
}
