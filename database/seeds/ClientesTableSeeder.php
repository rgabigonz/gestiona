<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clientes')->insert([
            'nombre' => 'EL OMBU SRL',
            'direccion' => 'RUTA 45 KM 4 1/2 0 SANTO DOMINGO, JUJUY',
            'correo_electronico' => 'nolose@gmail.com',
            'telefono' => 'SIN TELEFONO',
            'tipo_documento' => 2,
            'numero_documento' => '30-70744956-6',
        ]);

        //factory('App\Cliente', 10)->create();
    }
}
