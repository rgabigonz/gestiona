<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasDebitosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas_debitos_detalle', function (Blueprint $table) {
            $table->unsignedInteger('nota_debito_id');
            $table->unsignedInteger('concepto_id')->nullable();
            $table->decimal('importe', 8, 2);
            $table->timestamps();

            $table->foreign('concepto_id')->references('id')->on('conceptos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notas_debitos_detalle');
    }
}
