<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas_pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('anio_id'); // Ultimo Codigo por anio
            $table->unsignedInteger('anio_actual'); // Anio del codigo            
            $table->unsignedInteger('cliente_id');
            $table->unsignedInteger('vendedor_venta_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->char('estado', 2)->default('PE'); //PE = Pendiente, AN = Anulado, CO = Confirmado, FI = Finalizado
            $table->decimal('total', 8, 2);
            $table->date('fecha');
            $table->string('numero_factura')->nullable();
            $table->string('lugar_entrega')->nullable();
            $table->string('obs')->nullable();
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('vendedor_venta_id')->references('id')->on('vendedores');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notas_pedidos');
    }
}
