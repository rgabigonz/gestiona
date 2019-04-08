<?php

namespace App\Http\Controllers\Api;

use App\FormaVenta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormaVentaController extends Controller
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
            $formas_venta = FormaVenta::orderBy('descripcion', 'asc')->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $formas_venta = FormaVenta::where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('descripcion', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $formas_venta->total(),
                'current_page'  => $formas_venta->currentPage(),
                'per_page'      => $formas_venta->perPage(),
                'last_page'     => $formas_venta->lastPage(),
                'from'          => $formas_venta->firstItem(),
                'to'            => $formas_venta->lastItem(),
            ],
            'formas_venta' => $formas_venta
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
        $this->validate($request, [
            'descripcion' => 'required|string'
        ], [
            'descripcion.required' => 'La descripcion es requerida'
        ]);

        return FormaVenta::create([
            'descripcion' => $request['descripcion']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormaVenta  $formaVenta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $forma_pago = FormaVenta::findOrFail($id);

        $this->validate($request, [
            'descripcion' => 'required|string'
        ], [
            'descripcion.required' => 'La descripcion es requerida'
        ]);

        $forma_pago->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $forma_pago = FormaVenta::findOrFail($id);
        $forma_pago->estado = 'D';
        $forma_pago->update();
    }

    public function activar(Request $request, $id)
    {
        $forma_pago = FormaVenta::findOrFail($id);
        $forma_pago->estado = 'A';
        $forma_pago->update();
    }
}
