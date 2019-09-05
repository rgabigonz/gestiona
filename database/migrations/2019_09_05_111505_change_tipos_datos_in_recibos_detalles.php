<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTiposDatosInRecibosDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recibos_detalles', function (Blueprint $table) {
            $table->float('importe', 12, 2)->change();
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
            $table->decimal('importe', 8, 2)->change();
        });
    }
}
