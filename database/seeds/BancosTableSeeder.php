<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BancosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bancos')->insert([
            'nombre' => 'MACRO'
        ]);

        DB::table('bancos')->insert([
            'nombre' => 'GALICIA'
        ]);

        DB::table('bancos')->insert([
            'nombre' => 'HSBC'
        ]);                
    }
}
