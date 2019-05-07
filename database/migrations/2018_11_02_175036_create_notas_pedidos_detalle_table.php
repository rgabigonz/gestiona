<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasPedidosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas_pedidos_detalle', function (Blueprint $table) {
            $table->unsignedInteger('nota_pedido_id');
            $table->unsignedInteger('producto_id');
            $table->decimal('cantidad', 8, 2);
            $table->decimal('precio', 8, 2);
            $table->decimal('flete', 8, 2)->default(0);
            $table->decimal('comision_venta', 8, 2)->default(0);
            $table->string('obs')->nullable();
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->primary(['nota_pedido_id', 'producto_id']);

            $table->foreign('nota_pedido_id')->references('id')->on('notas_pedidos');
            $table->foreign('producto_id')->references('id')->on('productos');
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
        Schema::dropIfExists('notas_pedidos_detalle');
    }
}
