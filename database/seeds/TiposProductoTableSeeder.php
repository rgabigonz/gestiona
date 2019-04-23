<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposProductoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_productos')->insert([
            'descripcion' => 'AGROQUIMICOS',
            'iva' => 21
        ]);

        DB::table('tipo_productos')->insert([
            'descripcion' => 'FERTILIZANTES',
            'iva' => 10.5
        ]);          
    }
}
