<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIvaToNotasPedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_pedidos', function (Blueprint $table) {
            $table->decimal('total_iva105', 8, 2)->default(0);
            $table->decimal('total_iva21', 8, 2)->default(0);
            $table->decimal('total_siniva', 8, 2);
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
            $table->dropColumn('total_iva105');
            $table->dropColumn('total_iva21');
            $table->dropColumn('total_siniva');
        });
    }
}
