<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasCreditoDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas_creditos_detalle', function (Blueprint $table) {
            $table->unsignedInteger('nota_credito_id');
            $table->unsignedInteger('concepto_id')->nullable();
            $table->decimal('importe', 8, 2);
            $table->timestamps();

            $table->foreign('concepto_id')->references('id')->on('conceptos');
            $table->foreign('nota_credito_id')->references('id')->on('notas_creditos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nota_credito_detalles');
    }
}
