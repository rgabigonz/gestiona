<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepositosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('depositos')->insert([
            'descripcion' => 'DEPOSITO PROPIO',
        ]);
    }
}
