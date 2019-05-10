<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendedoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vendedores')->insert([
            'nombre' => 'AGRO PROYECCIONES SRL',
            'direccion' => 'EL TIPAL, SALTA',
            'correo_electronico' => 'agroproyecciones@gmail.com',
            'telefono' => 'SIN TELEFONO'
        ]);

        DB::table('configuracion')->insert([
            'id' => 1,
            'vendedor_gestion_id' => 1,
            'sucursal_id' => 1
        ]);        
    }
}
