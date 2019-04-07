<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TiposDocumentoTableSeeder::class);
        //$this->call(UsersTableSeeder::class);
        $this->call(ProductosTableSeeder::class);
        $this->call(ClientesTableSeeder::class);
        $this->call(ProveedoresTableSeeder::class);
        //$this->call(NotasPedidoTableSeeder::class); 
    }
}
