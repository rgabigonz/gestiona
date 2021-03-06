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
        $this->call(SucursalesTableSeeder::class);
        $this->call(BancosTableSeeder::class);
        $this->call(TiposDocumentoTableSeeder::class);
        $this->call(DepositosTableSeeder::class);
        $this->call(FormasPagoTableSeeder::class);
        $this->call(FormasVentaTableSeeder::class);
        //$this->call(CondicionesPagoTableSeeder::class);
        $this->call(TiposProductoTableSeeder::class);
        //$this->call(UsersTableSeeder::class);
        $this->call(ProductosTableSeeder::class);
        $this->call(ClientesTableSeeder::class);
        $this->call(ProveedoresTableSeeder::class);
        $this->call(VendedoresTableSeeder::class);
    }
}
