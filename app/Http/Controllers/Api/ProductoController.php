<?php

namespace App\Http\Controllers\Api;

use App\Producto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductoController extends Controller
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
            $productos = Producto::orderBy('nombre', 'asc')->paginate(15);//where('state', '=', 'A')
        } 
        else {
            $productos = Producto::where($sCriterio, 'like', '%' . $sBuscar . '%')//where('state', '=', 'A')
            ->orderBy('nombre', 'asc')
            ->paginate(15);
        }

        return [
            'pagination' => [
                'total'         => $productos->total(),
                'current_page'  => $productos->currentPage(),
                'per_page'      => $productos->perPage(),
                'last_page'     => $productos->lastPage(),
                'from'          => $productos->firstItem(),
                'to'            => $productos->lastItem(),
            ],
            'productos' => $productos
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
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0.01',
            'stk_min' => 'required|numeric|min:0.01',
            'stk_max' => 'required|numeric|min:0.01',
        ], [
            'nombre.required' => 'El nombre es requerido',
            'descripcion.required' => 'La descripcion es requerida',
            'precio.required' => 'El precio es requerido',
            'precio.min' => 'El precio debe ser mayor a 0',
            'stk_min.required' => 'El stock minimo es requerido',
            'stk_min.min' => 'El stock minimo debe ser mayor a 0',
            'stk_max.required' => 'El stock maximo es requerido',
            'stk_max.min' => 'El stock maximo ser mayor a 0',
        ]);

        return Producto::create([
            'nombre' => $request['nombre'],
            'descripcion' => $request['descripcion'],
            'precio' => $request['precio'],
            'stk_min' => $request['stk_min'],
            'stk_max' => $request['stk_max'],
            'tipo_producto' => $request['tipo_producto']
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $this->validate($request, [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0.01',
            'stk_min' => 'required|numeric|min:0.01',
            'stk_max' => 'required|numeric|min:0.01',
        ], [
            'nombre.required' => 'El nombre es requerido',
            'descripcion.required' => 'La descripcion es requerida',
            'precio.required' => 'El precio es requerido',
            'precio.min' => 'El precio debe ser mayor a 0',
            'stk_min.required' => 'El stock minimo es requerido',
            'stk_min.min' => 'El stock minimo debe ser mayor a 0',
            'stk_max.required' => 'El stock maximo es requerido',
            'stk_max.min' => 'El stock maximo ser mayor a 0',
        ]);

        $producto->update($request->all());
    }

    public function desactivar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->estado = 'D';
        $producto->update();
    }

    public function activar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->estado = 'A';
        $producto->update();
    }

    public function devuelveDatosProducto(Request $request, $id)
    {
        $datoProducto = Producto::findOrFail($id);
        return [
            'datoProducto' => $datoProducto
        ];
    }    

    public function cargaProductos()
    {
        $productos = Producto::orderBy('nombre', 'asc')->get();
        return [
            'productos' => $productos
        ];
    }       
}
