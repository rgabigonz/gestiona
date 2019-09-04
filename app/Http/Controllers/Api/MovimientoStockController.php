<?php

namespace App\Http\Controllers\Api;

use App\MovimientoStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            ->select('movimientos_stock.*', 'depositos.descripcion as descripcion_deposito', 'productos.nombre as producto_nombre')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        } 
        else {
            $movimientosstock = MovimientoStock::join('depositos', 'movimientos_stock.deposito_id', '=', 'depositos.id')
            ->join('productos', 'movimientos_stock.producto_id', '=', 'productos.id')
            ->select('movimientos_stock.*', 'depositos.descripcion as descripcion_deposito', 'productos.nombre as producto_nombre')
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MovimientoStock  $movimientoStock
     * @return \Illuminate\Http\Response
     */
    public function show(MovimientoStock $movimientoStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MovimientoStock  $movimientoStock
     * @return \Illuminate\Http\Response
     */
    public function edit(MovimientoStock $movimientoStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MovimientoStock  $movimientoStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MovimientoStock $movimientoStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MovimientoStock  $movimientoStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(MovimientoStock $movimientoStock)
    {
        //
    }
}
