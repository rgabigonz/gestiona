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
            'nombre' => 'AGRO PROYECCIONES DRL',
            'direccion' => 'EL TIPAL, SALTA',
            'correo_electronico' => 'agroproyecciones@gmail.com',
            'telefono' => 'SIN TELEFONO'
        ]);

    }
}
