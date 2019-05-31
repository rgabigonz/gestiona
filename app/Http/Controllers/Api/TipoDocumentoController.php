<?php

namespace App\Http\Controllers\Api;

use App\TipoDocumento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipoDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\TipoDocumento  $tipodocumento
     * @return \Illuminate\Http\Response
     */
    public function show(TipoDocumento $tipodocumento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TipoDocumento  $tipodocumento
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoDocumento $tipodocumento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TipoDocumento  $tipodocumento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoDocumento $tipodocumento)
    {
        //
    }

    public function cargaTD()
    {
        $tiposDocumento = TipoDocumento::orderBy('descripcion', 'asc')->where('estado', '=', 'A')->get();
        return [
            'tiposDocumento' => $tiposDocumento
        ];
    }

}
