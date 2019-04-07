<?php

use Illuminate\Database\Seeder;

class NotasPedidoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\NotaPedido', 10)->create();
    }
}
