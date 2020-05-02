<?php

namespace App\Http\Controllers\Api;

use App\MovimientoStock;
use App\StockProducto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MovimientoStockController extends Controller
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
            $movimientosstock = MovimientoStock::join('depositos', 'movimientos_stock.deposito_id', '=', 'depositos.id')
            ->join('productos', 'movimientos_stock.producto_id', '=', 'productos.id')
            //->leftjoin('notas_pedidos', 'movimientos_stock.documento_id', '=', 'notas_pedidos.id')
            //->leftjoin('ordenes_compras', 'movimientos_stock.documento_id', '=', 'ordenes_compras.id')
            ->leftjoin('notas_pedidos', function ($join) {
                $join->on('movimientos_stock.documento_id', '=', 'notas_pedidos.id')
                     ->where('movimientos_stock.tipo_documento', '=', 'NV');
            })
            ->leftjoin('ordenes_compras', function ($join) {
                $join->on('movimientos_stock.documento_id', '=', 'ordenes_compras.id')
                     ->where('movimientos_stock.tipo_documento', '=', 'OC');
            })            
            ->leftjoin('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
            ->select('movimientos_stock.*', 'depositos.descripcion as descripcion_deposito', 
                     'productos.nombre as producto_nombre', 'productos.descripcion as producto_descripcion',
                     'clientes.nombre as nombre_cliente', 'notas_pedidos.anio_id as nota_ventaAID', 
                     'notas_pedidos.anio_actual as nota_ventaAA', 'ordenes_compras.anio_id as nota_pedidoAID', 
                     'ordenes_compras.anio_actual as nota_pedidoAA')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        } 
        else {
            $movimientosstock = MovimientoStock::join('depositos', 'movimientos_stock.deposito_id', '=', 'depositos.id')
            ->join('productos', 'movimientos_stock.producto_id', '=', 'productos.id')
            //->leftjoin('notas_pedidos', 'movimientos_stock.documento_id', '=', 'notas_pedidos.id')
            ->leftjoin('notas_pedidos', function ($join) {
                $join->on('movimientos_stock.documento_id', '=', 'notas_pedidos.id')
                     ->where('movimientos_stock.tipo_documento', '=', 'NV');
            })
            ->leftjoin('ordenes_compras', function ($join) {
                $join->on('movimientos_stock.documento_id', '=', 'ordenes_compras.id')
                     ->where('movimientos_stock.tipo_documento', '=', 'OC');
            })             
            ->leftjoin('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
            ->select('movimientos_stock.*', 'depositos.descripcion as descripcion_deposito', 
                     'productos.nombre as producto_nombre', 'productos.descripcion as producto_descripcion',
                     'clientes.nombre as nombre_cliente', 'notas_pedidos.anio_id as nota_ventaAID', 
                     'notas_pedidos.anio_actual as nota_ventaAA', 'ordenes_compras.anio_id as nota_pedidoAID', 
                     'ordenes_compras.anio_actual as nota_pedidoAA')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $movimientosstock->total(),
                'current_page'  => $movimientosstock->currentPage(),
                'per_page'      => $movimientosstock->perPage(),    
                'last_page'     => $movimientosstock->lastPage(),
                'from'          => $movimientosstock->firstItem(),
                'to'            => $movimientosstock->lastItem(),
            ],
            'movimientosstock' => $movimientosstock
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

        $fecha_movimiento = \Carbon\Carbon::now();
        $fecha_movimiento = $fecha_movimiento->format('Y-m-d');
     
        $this->validate($request, [
            'producto_id' => 'required|numeric|not_in:0',
            'deposito_id' => 'required|numeric|not_in:0',
            'cantidad' => 'required|numeric|min:0|not_in:0',
            'descripcion' => 'required|string'
        ], [
            'producto_id.not_in' => 'El producto es requerido',
            'deposito_id.not_in' => 'El deposito es requerido',
            'cantidad.required' => 'La cantidad es requerida',
            'cantidad.not_in' => 'La cantidad debe ser mayor a 0',
            'descripcion.required' => 'La descripcion es requerida'
        ]);

        return MovimientoStock::create([
            'producto_id' => $request['producto_id'],
            'deposito_id' => $request['deposito_id'],
            'user_id' => $user->id,
            'fecha' => $fecha_movimiento,
            'cantidad' => $request['cantidad'],
            'descripcion' => $request['descripcion'],
            'tipo' => $request['tipo'],
            'tipo_documento' => $request['tipo_documento'],
            'documento_id' => $request['documento_id']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MovimientoStock  $movimientoStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $movimiento_stock = MovimientoStock::findOrFail($id);

        $this->validate($request, [
            'producto_id' => 'required|numeric|not_in:0',
            'deposito_id' => 'required|numeric|not_in:0',
            'cantidad' => 'required|numeric|min:0|not_in:0',
            'descripcion' => 'required|string'
        ], [
            'producto_id.not_in' => 'El producto es requerido',
            'deposito_id.not_in' => 'El deposito es requerido',
            'cantidad.required' => 'La cantidad es requerida',
            'cantidad.not_in' => 'La cantidad debe ser mayor a 0',
            'descripcion.required' => 'La descripcion es requerida'
        ]);

        $movimiento_stock->update($request->all());
    }

    public function confirmaMovimientoStock(Request $request, $id)
    {
        $MovimientoStock = MovimientoStock::findOrFail($id);
        $MovimientoStock->estado = 'CO';
        $MovimientoStock->update();

        if ($MovimientoStock->id > 0) {

            if ($MovimientoStock->tipo == 'I')
                $cantidad_movimiento = ($MovimientoStock->cantidad * 1);
            if ($MovimientoStock->tipo == 'E')
                $cantidad_movimiento = ($MovimientoStock->cantidad * -1);

                $stock_producto = StockProducto::where('producto_id', '=', $MovimientoStock->producto_id)
                                                ->where('deposito_id', '=', $MovimientoStock->deposito_id)
                                                ->get();

                if(count($stock_producto) > 0) {
                    $cantidad_total = $stock_producto[0]->cantidad + $cantidad_movimiento;
                    DB::table('stock_productos')
                        ->where('producto_id', '=', $stock_producto[0]->producto_id)
                        ->where('deposito_id', '=', $stock_producto[0]->deposito_id)
                        ->update(['cantidad' => $cantidad_total]);
                } else {
                    return StockProducto::create([
                        'producto_id' => $MovimientoStock->producto_id,
                        'deposito_id' => $MovimientoStock->deposito_id,
                        'cantidad' => $cantidad_movimiento
                    ]);
                } 
        }
    }    

    public function anulaMovimientoStock(Request $request, $id)
    {
        $MovimientoStock = MovimientoStock::findOrFail($id);
        $MovimientoStock->estado = 'AN';
        $MovimientoStock->update();
    }

    public function devuelveMovimientosStock(Request $request)
    {
        $deposito = $request->deposito;
        $producto = $request->producto;
        
        $MovimientosStock = MovimientoStock::join('productos', 'movimientos_stock.producto_id', '=', 'productos.id')
        ->join('depositos', 'movimientos_stock.deposito_id', '=', 'depositos.id')
        ->leftjoin('notas_pedidos', 'movimientos_stock.documento_id', '=', 'notas_pedidos.id')
        ->leftjoin('ordenes_compras', 'movimientos_stock.documento_id', '=', 'ordenes_compras.id')
        ->select('movimientos_stock.*', 'productos.nombre as nombre_producto', 'productos.descripcion as descripcion_producto', 'depositos.descripcion as nombre_deposito', 
                 'notas_pedidos.anio_id as nota_ventaAID', 'notas_pedidos.anio_actual as nota_ventaAA',
                 'ordenes_compras.anio_id as orden_compraAID', 'ordenes_compras.anio_actual as orden_compraAA')
        ->where('movimientos_stock.deposito_id', '=', $deposito)
        ->where('movimientos_stock.producto_id', '=', $producto)->get();

        return [
            'MovimientosStock' => $MovimientosStock
        ];
    }      
}
