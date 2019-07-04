<?php

namespace App\Http\Controllers\Api;

use App\Cotizacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class CotizacionController extends Controller
{
    public function devuelveCotizacion()
    {
        $client = new Client();

        $token = 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1ODc2NDI4NTQsInR5cGUiOiJleHRlcm5hbCIsInVzZXIiOiJhZHMuZ2dvbnphbGV6QGdtYWlsLmNvbSJ9.BtoG0dCyhFrCPlG0Q4SNBkW9oE_B-l0HzA1Uahk-sSXAaW9RRO8RmEH2sVvJsiCqeX1cuR1wcC8nt-5brqL_Bw';

        $headers = [
            'Authorization' => 'BEARER ' . $token,
            'Accept'        => '*/*',
            'Connection'        => 'keep-alive'
        ];

        $cotizacion = $client->request('GET', 'https://api.estadisticasbcra.com/usd_of_minorista', [
            'headers' => $headers
        ])->getBody();

        $cotizacion_oficial = json_decode($cotizacion->getContents(), true);

        $cantidad_items = count($cotizacion_oficial);

        // Sino existe la cotizacion la genero
        $fecha_del_dia_menos_1 = $cotizacion_oficial[$cantidad_items -1]['d'];

        $cotizacion_del_dia_1 = Cotizacion::whereDate('fecha', '=', $fecha_del_dia_menos_1)->get();

        if (count($cotizacion_del_dia_1) <= 0) {
            Cotizacion::create([
                'fecha' => $cotizacion_oficial[$cantidad_items -1]['d'],
                'precio_dolar' => $cotizacion_oficial[$cantidad_items -1]['v']
            ]);
        }
        // Sino existe la cotizacion la genero
                
        return [
            'cotizacion_oficial' => $cotizacion_oficial[$cantidad_items -1]
        ];
    } 
}
