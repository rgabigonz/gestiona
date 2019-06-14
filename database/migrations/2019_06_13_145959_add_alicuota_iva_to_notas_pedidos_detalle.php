<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlicuotaIvaToNotasPedidosDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_pedidos_detalle', function (Blueprint $table) {
            $table->decimal('alicuota_iva', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_pedidos_detalle', function (Blueprint $table) {
            $table->dropColumn('alicuota_iva');
        });
    }
}
