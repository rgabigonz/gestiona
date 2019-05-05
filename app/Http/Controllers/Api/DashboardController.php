<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio = date('Y');

        $compras = DB::table('ordenes_compras as oc')
        ->select(DB::raw('MONTH(oc.fecha) as mes'), DB::raw('YEAR(oc.fecha) as anio'), DB::raw('SUM(oc.total) as total'))
        ->whereYear('oc.fecha', $anio)
        ->where('oc.estado', '=', 'CO')
        ->groupBy(DB::raw('MONTH(oc.fecha)'), DB::raw('YEAR(oc.fecha)'))
        ->get();
 
        $ventas = DB::table('notas_pedidos as np')
        ->select(DB::raw('MONTH(np.fecha) as mes'), DB::raw('YEAR(np.fecha) as anio'), DB::raw('SUM(np.total) as total'))
        ->whereYear('np.fecha', $anio)
        ->where('np.estado', '=', 'CO')
        ->groupBy(DB::raw('MONTH(np.fecha)'), DB::raw('YEAR(np.fecha)'))
        ->get();
 
        return ['compras'=>$compras, 'ventas'=>$ventas, 'anio'=>$anio];      
     }
}
