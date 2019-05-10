<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecibosDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recibos_detalles', function (Blueprint $table) {
            $table->unsignedInteger('recibo_id');
            $table->char('tipo_pago', 2)->default('CH'); //CH = Cheque, CO = Contado
            $table->date('fecha_cheque')->nullable();
            $table->unsignedInteger('numero_cheque')->nullable();
            $table->unsignedInteger('banco_id')->nullable();
            $table->decimal('importe', 8, 2);
            $table->timestamps();

            $table->foreign('banco_id')->references('id')->on('bancos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recibo_detalles');
    }
}
