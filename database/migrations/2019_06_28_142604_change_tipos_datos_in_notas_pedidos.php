<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTiposDatosInNotasPedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_pedidos', function (Blueprint $table) {
            $table->float('total', 12, 2)->change();
            $table->float('total_iva105', 12, 2)->change();
            $table->float('total_iva21', 12, 2)->change();
            $table->float('total_siniva', 12, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_pedidos', function (Blueprint $table) {
            $table->decimal('total', 8, 2)->change();
            $table->decimal('total_iva105', 8, 2)->change();
            $table->decimal('total_iva21', 8, 2)->change();
            $table->decimal('total_siniva', 8, 2)->change();
        });
    }
}
