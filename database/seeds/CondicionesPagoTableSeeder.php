<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CondicionesPagoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('condicion_pagos')->insert([
            'descripcion' => '40% - 30 DIAS',
        ]);

        DB::table('condicion_pagos')->insert([
            'descripcion' => '20% - 60 DIAS',
        ]);      
        
        DB::table('condicion_pagos')->insert([
            'descripcion' => '40% - 90 DIAS',
        ]);        
    }
}
