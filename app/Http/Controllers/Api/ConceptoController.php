<?php

namespace App\Http\Controllers\Api;

use App\Concepto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConceptoController extends Controller
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
            $conceptos = Concepto::orderBy('descripcion', 'asc')->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $conceptos = Concepto::where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('descripcion', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $conceptos->total(),
                'current_page'  => $conceptos->currentPage(),
                'per_page'      => $conceptos->perPage(),
                'last_page'     => $conceptos->lastPage(),
                'from'          => $conceptos->firstItem(),
                'to'            => $conceptos->lastItem(),
            ],
            'conceptos' => $conceptos
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
            'descripcion' => 'required|string|max:100'
        ], [
            'descripcion.required' => 'La descripcion es requerida'
        ]);

        return Concepto::create([
            'descripcion' => $request['descripcion'],
            'tipo_comprobante' => $request['tipo_comprobante']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Concepto  $concepto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $concepto = Concepto::findOrFail($id);

        $this->validate($request, [
            'descripcion' => 'required|string|max:100'
        ], [
            'descripcion.required' => 'La descripcion es requerida'
        ]);

        $concepto->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $concepto = Concepto::findOrFail($id);
        $concepto->estado = 'D';
        $concepto->update();
    }

    public function activar(Request $request, $id)
    {
        $concepto = Concepto::findOrFail($id);
        $concepto->estado = 'A';
        $concepto->update();
    }
    
    public function cargaConceptosND()
    {
        $conceptos = Concepto::orderBy('descripcion', 'asc')
        ->where('estado', '=', 'A')
        ->where('tipo_comprobante', '=', 'ND')
        ->get();
        return [
            'conceptos' => $conceptos
        ];
    }

    public function cargaConceptosNC()
    {
        $conceptos = Concepto::orderBy('descripcion', 'asc')
        ->where('estado', '=', 'A')
        ->where('tipo_comprobante', '=', 'NC')
        ->get();
        return [
            'conceptos' => $conceptos
        ];
    }    
}
