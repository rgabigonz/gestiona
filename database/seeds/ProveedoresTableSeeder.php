<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('proveedores')->insert([
            'nombre' => 'Cofco Argentina S.A.',
            'direccion' => 'B1603AAC, Francisco Narciso de Laprida 3125, B1603AAC Villa Martelli, Buenos Aires',
            'correo_electronico' => 'nolose@gmail.com',
            'telefono' => '011 4468-8200',
            'tipo_documento' => 2,
            'numero_documento' => '30-70779108-6',
        ]);
        
        //factory('App\Proveedor', 10)->create();
    }
}
