<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormasPagoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('forma_pagos')->insert([
            'descripcion' => 'CONTADO EFECTIVO',
        ]);
    }
}
