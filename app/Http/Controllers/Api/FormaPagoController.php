<?php

namespace App\Http\Controllers\Api;

use App\FormaPago;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormaPagoController extends Controller
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
            $formas_pago = FormaPago::orderBy('descripcion', 'asc')->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $formas_pago = FormaPago::where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('descripcion', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $formas_pago->total(),
                'current_page'  => $formas_pago->currentPage(),
                'per_page'      => $formas_pago->perPage(),
                'last_page'     => $formas_pago->lastPage(),
                'from'          => $formas_pago->firstItem(),
                'to'            => $formas_pago->lastItem(),
            ],
            'formas_pago' => $formas_pago
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

        return FormaPago::create([
            'descripcion' => $request['descripcion']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormaPago  $formaPago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $forma_pago = FormaPago::findOrFail($id);

        $this->validate($request, [
            'descripcion' => 'required|string'
        ], [
            'descripcion.required' => 'La descripcion es requerida'
        ]);

        $forma_pago->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $forma_pago = FormaPago::findOrFail($id);
        $forma_pago->estado = 'D';
        $forma_pago->update();
    }

    public function activar(Request $request, $id)
    {
        $forma_pago = FormaPago::findOrFail($id);
        $forma_pago->estado = 'A';
        $forma_pago->update();
    }
}
