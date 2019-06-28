<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTiposDatosInOrdenesComprasDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordenes_compras_detalle', function (Blueprint $table) {
            $table->float('precio_total', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes_compras_detalle', function (Blueprint $table) {
            $table->decimal('precio_total', 8, 2)->change();
        });
    }
}
