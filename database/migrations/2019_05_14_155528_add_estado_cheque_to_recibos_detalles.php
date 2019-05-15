<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstadoChequeToRecibosDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recibos_detalles', function (Blueprint $table) {
            $table->date('fecha_cobro_cheque')->nullable()->after('importe');
            $table->char('estado_cheque', 2)->default('PE')->after('importe'); //PE = Pendiente, CO = Cobrado            
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
            $table->dropColumn('fecha_cobro_cheque');
            $table->dropColumn('estado_cheque');
        });
    }
}
