<?php

namespace App\Http\Controllers\Api;

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

        $cotizacion = $client->request('GET', 'https://api.estadisticasbcra.com/usd_of', [
            'headers' => $headers
        ])->getBody();

        $cotizacion_oficial = json_decode($cotizacion->getContents(), true);

        $cantidad_items = count($cotizacion_oficial);
                
        //return $cotizacion_oficial;
        return [
            'cotizacion_oficial' => $cotizacion_oficial[$cantidad_items -1]
        ];

        //dd($cotizacion_2);
    } 
}
