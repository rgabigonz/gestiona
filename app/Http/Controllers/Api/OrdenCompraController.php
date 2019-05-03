<?php

namespace App\Http\Controllers\Api;

use App\OrdenCompra;
use App\OrdenCompraDetalle;
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

        if(empty($sBuscar)) {
            $ordenes_compras = OrdenCompra::join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
            ->select('ordenes_compras.*', 'proveedores.nombre as nombre_proveedor')
            ->orderBy('created_at', 'asc')
            ->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $ordenes_compras = OrdenCompra::join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
            ->select('ordenes_compras.*', 'proveedores.nombre as nombre_proveedor')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')
            ->orderBy('created_at', 'asc')
            ->paginate(15);//where('state', '=', 'A')
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
        
        $orden_compra->total = $request->total_orden;
        $orden_compra->fecha = $fecha_compra->format('Y-m-d');
        $orden_compra->numero_negocio = $request->numero_negocio;
        $orden_compra->obs = $request->obs;

        //Si es de cliente
        if ($request->tipo == 'CL') {
            $orden_compra->cliente_id = $request->codigo_cliente;
            $orden_compra->vendedor_venta_id = $request->codigo_vendedor_venta;
            $orden_compra->vendedor_gestion_id = $request->codigo_vendedor_gestion;
        }

        $orden_compra->save();

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $orden_compra_item = new OrdenCompraDetalle();
            $orden_compra_item->producto_id = $request->items[$i]['cod'];
            $orden_compra_item->cantidad = $request->items[$i]['cantidad'];
            $orden_compra_item->precio = $request->items[$i]['precio'];
            $orden_compra_item->flete = $request->items[$i]['flete'];
            $orden_compra_item->obs = $request->items[$i]['obs'];
            $orden_compra_item->comision_venta = $request->items[$i]['comision_venta'];
            $orden_compra_item->comision_gestion = $request->items[$i]['comision_gestion'];
            $orden_compra_item->precio_total  = ($request->items[$i]['flete'] + 
                                                 $request->items[$i]['comision_venta'] + 
                                                 $request->items[$i]['comision_gestion'] + 
                                                 ($request->items[$i]['precio'] * $request->items[$i]['cantidad']));
            $orden_compra_item->user_id = $user->id;
            $orden_compra->ordenCompraDetalle()->save($orden_compra_item);
        }
                
        return $orden_compra;
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        
        $OrdenCompra = OrdenCompra::findOrFail($id);
        $OrdenCompra->total = $request->total_orden;
        $OrdenCompra->numero_negocio = $request->numero_negocio;
        $OrdenCompra->obs = $request->obs;

        if (!empty($request->codigo_deposito))
            $OrdenCompra->deposito_id = $request->codigo_deposito;
        else
            $OrdenCompra->deposito_id = null;

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
            $OrdenCompra->vendedor_venta_id = $request->codigo_vendedor_venta;
            $OrdenCompra->vendedor_gestion_id = $request->codigo_vendedor_gestion;
        }

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
            $orden_compra_item->comision_venta = $request->items[$i]['comision_venta'];
            $orden_compra_item->comision_gestion = $request->items[$i]['comision_gestion'];
            $orden_compra_item->precio_total  = ($request->items[$i]['flete'] + 
                                                 $request->items[$i]['comision_venta'] + 
                                                 $request->items[$i]['comision_gestion'] + 
                                                 ($request->items[$i]['precio'] * $request->items[$i]['cantidad']));
            $orden_compra_item->user_id = $user->id;
            $orden_compra_item->save();
        }
        return $request->items;
    } 

    public function devuelveOrdenCompra(Request $request, $id)
    {
        $datoOrdenCompra = OrdenCompra::findOrFail($id);

        $datoOrdenCompraD = OrdenCompraDetalle::join('productos', 'ordenes_compras_detalle.producto_id', '=', 'productos.id')
        ->select('ordenes_compras_detalle.*', 'productos.nombre as nombre_producto')
        ->where('ordenes_compras_detalle.orden_compra_id', '=', $id)->get();

        return [
            'datoOrdenCompra' => $datoOrdenCompra,
            'datoOrdenCompraD' => $datoOrdenCompraD,
        ];
    }  

    public function confirmaOrdenCompra(Request $request, $id)
    {
        $OrdenCompra = OrdenCompra::findOrFail($id);
        $OrdenCompra->estado = 'CO';
        $OrdenCompra->update();
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
        ->join('proveedores', 'ordenes_compras.proveedor_id', '=', 'proveedores.id')
        ->select('ordenes_compras.*', 'clientes.nombre as nombre_cliente', 'clientes.direccion as direccion_cliente', 'clientes.telefono as telefono_cliente', 'clientes.correo_electronico as email_cliente', 'proveedores.nombre as nombre_proveedor', 'proveedores.direccion as direccion_proveedor', 'proveedores.telefono as telefono_proveedor', 'proveedores.correo_electronico as email_proveedor')
        ->where('ordenes_compras.id', '=', $id)->get();

        $datoOrdenCompraD = OrdenCompraDetalle::join('productos', 'ordenes_compras_detalle.producto_id', '=', 'productos.id')
        ->select('ordenes_compras_detalle.*', 'productos.nombre as nombre_producto')
        ->where('ordenes_compras_detalle.orden_compra_id', '=', $id)->get();
        
        $pdf = \PDF::loadView('reportesPDF.notapedidoproveedor', ['datoOrdenCompra'=>$datoOrdenCompra, 'datoOrdenCompraD'=>$datoOrdenCompraD]);

        return $pdf->download('notapedidoproveedor.pdf');
    }    
}
