<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstadoToMovimientosStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimientos_stock', function (Blueprint $table) {
            $table->char('estado', 2)->default('PE')->after('user_id'); //PE = Pendiente, AN = Anulado, CO = Confirmado
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movimientos_stock', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
}
