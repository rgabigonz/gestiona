<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrecioDolarChequeToRecibosDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recibos_detalles', function (Blueprint $table) {
            $table->decimal('precio_dolar_cheque', 8, 2)->default(0);
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
            $table->dropColumn('precio_dolar_cheque');
        });
    }
}
