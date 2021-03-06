<?php

namespace App\Http\Controllers\Api;

use App\OrdenCompra;
use App\OrdenCompraDetalle;
use App\MovimientoStock;
//use App\Configuracion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class OrdenCompraController extends Controller
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
        $sProducto = $request->producto;

        if(empty($sBuscar)) {
            if($sCriterio == 'AP') {
                if(empty($sProducto)) {
                    $ordenes_compras = OrdenCompra::join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
                    ->leftjoin('clientes', 'ordenes_compras.cliente_id', '=', 'clientes.id')
                    ->leftjoin('vendedores', 'ordenes_compras.vendedor_venta_id', '=', 'vendedores.id')
                    ->select('ordenes_compras.*', 'proveedores.nombre as nombre_proveedor', 
                            'clientes.nombre as nombre_cliente',
                            'vendedores.nombre as nombre_vendedor')
                    ->where('ordenes_compras.tipo', '<>', 'CL')
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
                } else {
                    $ordenes_compras = OrdenCompra::join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
                    ->join('ordenes_compras_detalle', 'ordenes_compras_detalle.orden_compra_id', '=', 'ordenes_compras.id')
                    ->leftjoin('clientes', 'ordenes_compras.cliente_id', '=', 'clientes.id')
                    ->leftjoin('vendedores', 'ordenes_compras.vendedor_venta_id', '=', 'vendedores.id')
                    ->select('ordenes_compras.*', 'proveedores.nombre as nombre_proveedor', 
                            'clientes.nombre as nombre_cliente',
                            'vendedores.nombre as nombre_vendedor')
                    ->where('ordenes_compras.tipo', '<>', 'CL')
                    ->where('ordenes_compras_detalle.producto_id', '=', $sProducto)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);                    
                }
            } else {
                if(empty($sProducto)) {
                    $ordenes_compras = OrdenCompra::join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
                    ->leftjoin('clientes', 'ordenes_compras.cliente_id', '=', 'clientes.id')
                    ->leftjoin('vendedores', 'ordenes_compras.vendedor_venta_id', '=', 'vendedores.id')
                    ->select('ordenes_compras.*', 'proveedores.nombre as nombre_proveedor', 
                            'clientes.nombre as nombre_cliente',
                            'vendedores.nombre as nombre_vendedor')
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
                } else {
                    $ordenes_compras = OrdenCompra::join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
                    ->join('ordenes_compras_detalle', 'ordenes_compras_detalle.orden_compra_id', '=', 'ordenes_compras.id')
                    ->leftjoin('clientes', 'ordenes_compras.cliente_id', '=', 'clientes.id')
                    ->leftjoin('vendedores', 'ordenes_compras.vendedor_venta_id', '=', 'vendedores.id')
                    ->select('ordenes_compras.*', 'proveedores.nombre as nombre_proveedor', 
                            'clientes.nombre as nombre_cliente',
                            'vendedores.nombre as nombre_vendedor')
                    ->where('ordenes_compras_detalle.producto_id', '=', $sProducto)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
                }                    
            }
        } 
        else {
            if(empty($sProducto)) {
                $ordenes_compras = OrdenCompra::join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
                ->leftjoin('clientes', 'ordenes_compras.cliente_id', '=', 'clientes.id')
                ->leftjoin('vendedores', 'ordenes_compras.vendedor_venta_id', '=', 'vendedores.id')
                ->select('ordenes_compras.*', 'proveedores.nombre as nombre_proveedor', 
                        'clientes.nombre as nombre_cliente',
                        'vendedores.nombre as nombre_vendedor')
                ->where($sCriterio, 'like', '%' . $sBuscar . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            } else {
                $ordenes_compras = OrdenCompra::join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
                ->join('ordenes_compras_detalle', 'ordenes_compras_detalle.orden_compra_id', '=', 'ordenes_compras.id')
                ->leftjoin('clientes', 'ordenes_compras.cliente_id', '=', 'clientes.id')
                ->leftjoin('vendedores', 'ordenes_compras.vendedor_venta_id', '=', 'vendedores.id')
                ->select('ordenes_compras.*', 'proveedores.nombre as nombre_proveedor', 
                        'clientes.nombre as nombre_cliente',
                        'vendedores.nombre as nombre_vendedor')
                ->where($sCriterio, 'like', '%' . $sBuscar . '%')
                ->where('ordenes_compras_detalle.producto_id', '=', $sProducto)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            }
        }

        return [
            'pagination' => [
                'total'         => $ordenes_compras->total(),
                'current_page'  => $ordenes_compras->currentPage(),
                'per_page'      => $ordenes_compras->perPage(),
                'last_page'     => $ordenes_compras->lastPage(),
                'from'          => $ordenes_compras->firstItem(),
                'to'            => $ordenes_compras->lastItem(),
            ],
            'ordenes_compras' => $ordenes_compras
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
        $fecha_compra = \Carbon\Carbon::parse($request->fecha_orden_compra);
        
        $cantidad_items = count($request->items);

        $anio_actual = \Carbon\Carbon::now();
        $anio_id = OrdenCompra::where('anio_actual', $anio_actual)->max('anio_id') + 1;

        $orden_compra = new OrdenCompra();

        $orden_compra->proveedor_id = $request->codigo_proveedor;
        $orden_compra->deposito_id = $request->codigo_deposito;
        $orden_compra->formapago_id = $request->codigo_forma_pago;
        $orden_compra->condicionpago_id = $request->codigo_condicion_pago;
        $orden_compra->user_id = $user->id;

        // ID Segun anio
        $orden_compra->anio_id = $anio_id;
        $orden_compra->anio_actual = $anio_actual->year;

        if (!empty($request->tipo))
            $orden_compra->tipo = $request->tipo;
        
        $orden_compra->fecha = $fecha_compra->format('Y-m-d');
        $orden_compra->numero_negocio = $request->numero_negocio;
        $orden_compra->lugar_entrega = $request->lugar_entrega;
        $orden_compra->obs = $request->obs;

        //Si es de cliente
        if ($request->tipo == 'CL') {
            $orden_compra->cliente_id = $request->codigo_cliente;

            if (!empty($request->codigo_vendedor_venta))
                $orden_compra->vendedor_venta_id = $request->codigo_vendedor_venta;

            $orden_compra->vendedor_gestion_id = $request->codigo_vendedor_gestion;
        }

        // Totales
        if (!empty($request->total_orden_siniva))
            $orden_compra->total_siniva = $request->total_orden_siniva;

        if (!empty($request->total_orden_21))
            $orden_compra->total_iva21  = $request->total_orden_21;

        if (!empty($request->total_orden_105))
            $orden_compra->total_iva105 = $request->total_orden_105;

        $orden_compra->total = $request->total_orden;

        $orden_compra->save();

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $orden_compra_item = new OrdenCompraDetalle();
            $orden_compra_item->producto_id = $request->items[$i]['cod'];
            $orden_compra_item->cantidad = $request->items[$i]['cantidad'];
            $orden_compra_item->precio = $request->items[$i]['precio'];
            $orden_compra_item->flete = $request->items[$i]['flete'];
            $orden_compra_item->obs = $request->items[$i]['obs'];
            $orden_compra_item->alicuota_iva = $request->items[$i]['alicuota_iva'];
            $orden_compra_item->comision_venta = $request->items[$i]['comision_venta'];
            $orden_compra_item->comision_gestion = $request->items[$i]['comision_gestion'];
            $orden_compra_item->precio_total  = ($request->items[$i]['flete'] + 
                                                 $request->items[$i]['comision_venta'] + 
                                                 $request->items[$i]['comision_gestion'] + 
                                                 $request->items[$i]['precio']) * $request->items[$i]['cantidad'];
            $orden_compra_item->user_id = $user->id;
            $orden_compra->ordenCompraDetalle()->save($orden_compra_item);
        }
                
        return $orden_compra;
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        
        $OrdenCompra = OrdenCompra::findOrFail($id);

        $OrdenCompra->numero_negocio = $request->numero_negocio;
        $OrdenCompra->lugar_entrega = $request->lugar_entrega;
        $OrdenCompra->obs = $request->obs;

        if (!empty($OrdenCompra->estado != 'CO')) {
            if (!empty($request->codigo_deposito))
                $OrdenCompra->deposito_id = $request->codigo_deposito;
            else
                $OrdenCompra->deposito_id = null;
        }

        if (!empty($request->codigo_forma_pago))            
            $OrdenCompra->formapago_id = $request->codigo_forma_pago;
        else
            $OrdenCompra->formapago_id = null;

        if (!empty($request->codigo_condicion_pago))            
            $OrdenCompra->condicionpago_id = $request->codigo_condicion_pago;
        else
            $OrdenCompra->condicionpago_id = null;

        //Si es de cliente
        if ($request->tipo == 'CL') {
            $OrdenCompra->cliente_id = $request->codigo_cliente;

            if (!empty($request->codigo_vendedor_venta))
                $OrdenCompra->vendedor_venta_id = $request->codigo_vendedor_venta;
            else
                $OrdenCompra->vendedor_venta_id = null;
                
            $OrdenCompra->vendedor_gestion_id = $request->codigo_vendedor_gestion;
        }

        // Totales
        if (!empty($request->total_orden_siniva))
            $OrdenCompra->total_siniva = $request->total_orden_siniva;

        if (!empty($request->total_orden_21))
            $OrdenCompra->total_iva21  = $request->total_orden_21;

        if (!empty($request->total_orden_105))
            $OrdenCompra->total_iva105 = $request->total_orden_105;

        $OrdenCompra->total = $request->total_orden;

        $OrdenCompra->update();

        $OrdenCompraDetalle = OrdenCompraDetalle::where('orden_compra_id', $id)->delete();

        $cantidad_items = count($request->items);

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $orden_compra_item = new OrdenCompraDetalle();
            $orden_compra_item->orden_compra_id = $id;
            $orden_compra_item->producto_id = $request->items[$i]['cod'];
            $orden_compra_item->cantidad = $request->items[$i]['cantidad'];
            $orden_compra_item->precio = $request->items[$i]['precio'];
            $orden_compra_item->flete = $request->items[$i]['flete'];
            $orden_compra_item->obs = $request->items[$i]['obs'];
            $orden_compra_item->alicuota_iva = $request->items[$i]['alicuota_iva'];
            $orden_compra_item->comision_venta = $request->items[$i]['comision_venta'];
            $orden_compra_item->comision_gestion = $request->items[$i]['comision_gestion'];
            $orden_compra_item->precio_total  = ($request->items[$i]['flete'] + 
                                                 $request->items[$i]['comision_venta'] + 
                                                 $request->items[$i]['comision_gestion'] + 
                                                 $request->items[$i]['precio']) * $request->items[$i]['cantidad'];
            $orden_compra_item->user_id = $user->id;
            $orden_compra_item->save();
        }
        return $request->items;
    } 

    public function devuelveOrdenCompra(Request $request, $id)
    {
        $datoOrdenCompra = OrdenCompra::findOrFail($id);

        $datoOrdenCompraD = OrdenCompraDetalle::join('productos', 'ordenes_compras_detalle.producto_id', '=', 'productos.id')
        ->join('tipo_productos', 'productos.tipo_producto', '=', 'tipo_productos.id')
        ->select('ordenes_compras_detalle.*', 'productos.nombre as nombre_producto', 'productos.descripcion as descripcion_producto', 'tipo_productos.iva as alicuota_iva')
        ->where('ordenes_compras_detalle.orden_compra_id', '=', $id)->get();

        return [
            'datoOrdenCompra' => $datoOrdenCompra,
            'datoOrdenCompraD' => $datoOrdenCompraD,
        ];
    }  

    public function confirmaOrdenCompra(Request $request, $id)
    {
        $user = auth('api')->user();

        $OrdenCompra = OrdenCompra::findOrFail($id);
        $OrdenCompra->estado = 'CO';
        $OrdenCompra->update();

        // Si actualizo
        if ($OrdenCompra->id > 0 && !empty($OrdenCompra->deposito_id)) {

            $datoOCDet = OrdenCompraDetalle::select('ordenes_compras_detalle.*')
                         ->where('ordenes_compras_detalle.orden_compra_id', '=', $id)
                         ->get();
            
            /*$Configuracion = Configuracion::get();

            if (!empty($OrdenCompra->deposito_id)) {
                $deposito_producto = $OrdenCompra->deposito_id;
            } else {
                $deposito_producto = $Configuracion[0]->deposito_id;
            }*/

            $fecha_movimiento = \Carbon\Carbon::parse($OrdenCompra->fecha);
            $fecha_movimiento = $fecha_movimiento->format('Y-m-d');

            $cantidad_items = count($datoOCDet);

            // Si encontro el detalle de la OC
            if ($cantidad_items > 0) {

                for($i = 0; $i < $cantidad_items; $i++)
                {                
                    $MovimientoStock = MovimientoStock::create([
                        'producto_id' => $datoOCDet[$i]->producto_id,
                        'deposito_id' => $OrdenCompra->deposito_id,//$deposito_producto,
                        'user_id' => $user->id,
                        //'estado' => 'CO',
                        'fecha' => $fecha_movimiento,
                        'cantidad' => $datoOCDet[$i]->cantidad,
                        'descripcion' => 'Movimiento automatico NP Proveedor',
                        'tipo' => 'I',
                        'tipo_documento' => 'OC',
                        'documento_id' => $OrdenCompra->id
                    ]);
                }
            }

            return $cantidad_items;
        }
    }    

    public function anulaOrdenCompra(Request $request, $id)
    {
        $OrdenCompra = OrdenCompra::findOrFail($id);
        $OrdenCompra->estado = 'AN';
        $OrdenCompra->update();
    }      
    
    public function NotaPedidoProveedorPDF(Request $request, $id)
    {
        $datoOrdenCompra = OrdenCompra::leftjoin('clientes', 'ordenes_compras.cliente_id', '=', 'clientes.id')
        ->leftjoin('depositos', 'ordenes_compras.deposito_id', '=', 'depositos.id')
        ->leftjoin('forma_pagos', 'ordenes_compras.formapago_id', '=', 'forma_pagos.id')
        ->leftjoin('condicion_pagos', 'ordenes_compras.condicionpago_id', '=', 'condicion_pagos.id')
        ->leftjoin('vendedores as vv', 'ordenes_compras.vendedor_venta_id', '=', 'vv.id')
        ->leftjoin('vendedores as vg', 'ordenes_compras.vendedor_gestion_id', '=', 'vg.id')
        ->join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
        ->select('ordenes_compras.*', 'clientes.nombre as nombre_cliente', 'clientes.direccion as direccion_cliente', 
                 'clientes.numero_documento as numero_documento', 'clientes.correo_electronico as email_cliente', 'proveedores.nombre as nombre_proveedor', 
                 'proveedores.direccion as direccion_proveedor', 'proveedores.telefono as telefono_proveedor', 
                 'proveedores.correo_electronico as email_proveedor', 'vv.nombre as nombre_vendedor_venta', 'vv.numero_documento as numero_documento_vendedor_venta', 
                 'vg.nombre as nombre_vendedor_gestion',
                 'depositos.descripcion as descripcion_deposito', 'forma_pagos.descripcion as descripcion_forma_pago', 
                 'condicion_pagos.descripcion as descripcion_condicion_pago')
        ->where('ordenes_compras.id', '=', $id)->get();

        $datoOrdenCompraD = OrdenCompraDetalle::join('productos', 'ordenes_compras_detalle.producto_id', '=', 'productos.id')
        ->select('ordenes_compras_detalle.*', 'productos.nombre as nombre_producto', 'productos.descripcion as descripcion_producto')
        ->where('ordenes_compras_detalle.orden_compra_id', '=', $id)->get();
        
        $pdf = \PDF::loadView('reportesPDF.notapedidoproveedor', ['datoOrdenCompra'=>$datoOrdenCompra, 'datoOrdenCompraD'=>$datoOrdenCompraD]);

        return $pdf->download('notapedidoproveedor.pdf');
    }    
}
