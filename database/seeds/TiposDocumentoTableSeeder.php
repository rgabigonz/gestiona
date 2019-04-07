<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposDocumentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos_documentos')->insert([
            'descripcion' => 'DNI',
        ]);

        DB::table('tipos_documentos')->insert([
            'descripcion' => 'CUIT',
        ]);

        DB::table('tipos_documentos')->insert([
            'descripcion' => 'CUIL',
        ]);

        DB::table('tipos_documentos')->insert([
            'descripcion' => 'PASAPORTE',
        ]);
    }
}
