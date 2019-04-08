<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormasVentaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('forma_ventas')->insert([
            'descripcion' => 'VENTA DIRECTA',
        ]);
    }
}
