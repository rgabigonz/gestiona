<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeClaveForaneaInRecibosDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recibos_detalles', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']);

            $table->foreign('proveedor_id')
            ->references('id')
            ->on('proveedores_simple');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recibos_detalles', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']);

            $table->foreign('proveedor_id')
            ->references('id')
            ->on('proveedores');
        });
    }
}
