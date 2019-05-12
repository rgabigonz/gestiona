<?php

namespace App\Http\Controllers\Api;

use App\NotaPedido;
use App\NotaPedidoDetalle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use PDF;

class NotaPedidoController extends Controller
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
            $notas_pedidos = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
            ->leftjoin('vendedores', 'notas_pedidos.vendedor_venta_id', '=', 'vendedores.id')
            ->select('notas_pedidos.*', 'clientes.nombre as nombre_cliente', 'vendedores.nombre as nombre_vendedor')
            ->orderBy('created_at', 'asc')
            ->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $notas_pedidos = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
            ->leftjoin('vendedores', 'notas_pedidos.vendedor_venta_id', '=', 'vendedores.id')
            ->select('notas_pedidos.*', 'clientes.nombre as nombre_cliente','vendedores.nombre as nombre_vendedor')
            ->where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('created_at', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $notas_pedidos->total(),
                'current_page'  => $notas_pedidos->currentPage(),
                'per_page'      => $notas_pedidos->perPage(),
                'last_page'     => $notas_pedidos->lastPage(),
                'from'          => $notas_pedidos->firstItem(),
                'to'            => $notas_pedidos->lastItem(),
            ],
            'notas_pedidos' => $notas_pedidos
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
        $fecha_pedido = \Carbon\Carbon::parse($request->fecha_nota_pedido);
        
        $cantidad_items = count($request->items);

        $anio_actual = \Carbon\Carbon::now();
        $anio_id = NotaPedido::where('anio_actual', $anio_actual)->max('anio_id') + 1;

        $nota_pedido = new NotaPedido();

        $nota_pedido->cliente_id = $request->codigo_cliente;
        $nota_pedido->user_id = $user->id;
        $nota_pedido->total = $request->total_pedido;
        $nota_pedido->numero_factura = $request->numero_factura;
        $nota_pedido->lugar_entrega = $request->lugar_entrega;
        $nota_pedido->fecha = $fecha_pedido->format('Y-m-d');

        if (!empty($request->codigo_vendedor_venta))
            $nota_pedido->vendedor_venta_id = $request->codigo_vendedor_venta;

        // ID Segun anio
        $nota_pedido->anio_id = $anio_id;
        $nota_pedido->anio_actual = $anio_actual->year;

        $nota_pedido->save();

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $nota_pedido_item = new NotaPedidoDetalle();
            $nota_pedido_item->producto_id = $request->items[$i]['cod'];
            $nota_pedido_item->cantidad = $request->items[$i]['cantidad'];
            $nota_pedido_item->precio = $request->items[$i]['precio'];
            $nota_pedido_item->flete = $request->items[$i]['flete'];
            $nota_pedido_item->comision_venta = $request->items[$i]['comision_venta'];
            $nota_pedido_item->user_id = $user->id;
            $nota_pedido->notaPedidoDetalle()->save($nota_pedido_item);
        }
                
        return $nota_pedido;
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        
        $NotaPedido = NotaPedido::findOrFail($id);
        $NotaPedido->total = $request->total_pedido;
        $NotaPedido->numero_factura = $request->numero_factura;
        $NotaPedido->lugar_entrega = $request->lugar_entrega;

        if (!empty($request->codigo_vendedor_venta))
            $NotaPedido->vendedor_venta_id = $request->codigo_vendedor_venta;

        $NotaPedido->update();

        $NotaPedidoDetalle = NotaPedidoDetalle::where('nota_pedido_id', $id)->delete();

        $cantidad_items = count($request->items);

        for($i = 0; $i < $cantidad_items; $i++)
        {
            $nota_pedido_item = new NotaPedidoDetalle();
            $nota_pedido_item->nota_pedido_id = $id;
            $nota_pedido_item->producto_id = $request->items[$i]['cod'];
            $nota_pedido_item->cantidad = $request->items[$i]['cantidad'];
            $nota_pedido_item->precio = $request->items[$i]['precio'];
            $nota_pedido_item->flete = $request->items[$i]['flete'];
            $nota_pedido_item->comision_venta = $request->items[$i]['comision_venta'];
            $nota_pedido_item->user_id = $user->id;
            $nota_pedido_item->save();
        }
        
        return $request->items;
    }    

    public function devuelveNotaPedido(Request $request, $id)
    {
        $datoNotaPedido = NotaPedido::findOrFail($id);

        $datoNotaPedidoD = NotaPedidoDetalle::join('productos', 'notas_pedidos_detalle.producto_id', '=', 'productos.id')
        ->select('notas_pedidos_detalle.*', 'productos.nombre as nombre_producto')
        ->where('notas_pedidos_detalle.nota_pedido_id', '=', $id)->get();

        return [
            'datoNotaPedido' => $datoNotaPedido,
            'datoNotaPedidoD' => $datoNotaPedidoD,
        ];
    }  

    public function confirmaNotaPedido(Request $request, $id)
    {
        $NotaPedido = NotaPedido::findOrFail($id);
        $NotaPedido->estado = 'CO';
        $NotaPedido->update();
    }    

    public function anulaNotaPedido(Request $request, $id)
    {
        $NotaPedido = NotaPedido::findOrFail($id);
        $NotaPedido->estado = 'AN';
        $NotaPedido->update();
    }

    public function NotaPedidoPDF(Request $request, $id)
    {
        $datoNotaPedido = NotaPedido::join('clientes', 'notas_pedidos.cliente_id', '=', 'clientes.id')
        ->leftjoin('vendedores', 'notas_pedidos.vendedor_venta_id', '=', 'vendedores.id')
        ->select('notas_pedidos.*', 'clientes.nombre as nombre_cliente', 'clientes.direccion as direccion_cliente', 
                 'clientes.telefono as telefono_cliente', 'clientes.correo_electronico as email_cliente',
                 'clientes.numero_documento as numero_documento', 'vendedores.nombre as nombre_vendedor_venta')
        ->where('notas_pedidos.id', '=', $id)->get();

        $datoNotaPedidoD = NotaPedidoDetalle::join('productos', 'notas_pedidos_detalle.producto_id', '=', 'productos.id')
        ->select('notas_pedidos_detalle.*', 'productos.nombre as nombre_producto')
        ->where('notas_pedidos_detalle.nota_pedido_id', '=', $id)->get();
        
        $pdf = \PDF::loadView('reportesPDF.notapedido', ['datoNotaPedido'=>$datoNotaPedido, 'datoNotaPedidoD'=>$datoNotaPedidoD]);

        return $pdf->download('notapedido.pdf');
    }
}
