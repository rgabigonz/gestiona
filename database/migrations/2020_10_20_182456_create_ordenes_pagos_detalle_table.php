<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenesPagosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_pagos_detalle', function (Blueprint $table) {
            $table->unsignedInteger('ordenpago_id');
            $table->char('tipo_pago', 2)->default('DP'); //DP = Deposito propio, D3 = Deposito 3ero, TP = Transferencia propia, T3 = Transferencia 3ero
                                                         //FC = Factura de Comiciones, DD = Deposito cheque al dia, DF = Deposito cheque a la fecha, CR = Cta Recaudadora 
            $table->decimal('importe', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_pagos_detalle');
    }
}
