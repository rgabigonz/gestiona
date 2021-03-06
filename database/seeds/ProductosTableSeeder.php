<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('productos')->insert([
            'nombre' => 'UREA EMBOLSADO',
            'descripcion' => 'DESCRIPCION UREA EMBOLSADO',
            'precio' => 482,
            'stk_min' => 1,
            'stk_max' => 10,
            'stk_actual' => 0,
            'tipo_producto' => 1
        ]);

        //factory('App\Producto', 10)->create();
    }
}
