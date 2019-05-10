<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sucursales')->insert([
            'punto_venta' => '0001',
            'nombre' => 'SUCURSAL CASA CENTRAL'
        ]);
    }
}
