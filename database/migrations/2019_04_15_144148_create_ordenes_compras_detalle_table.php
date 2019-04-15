<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenesComprasDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_compras_detalle', function (Blueprint $table) {
            $table->unsignedInteger('orden_compra_id');
            $table->unsignedInteger('producto_id');
            $table->decimal('cantidad', 8, 2);
            $table->decimal('precio', 8, 2);
            $table->unsignedInteger('user_id');            
            $table->timestamps();

            $table->primary(['orden_compra_id', 'producto_id']);

            $table->foreign('orden_compra_id')->references('id')->on('ordenes_compras');
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
        Schema::dropIfExists('ordenes_compras_detalle');
    }
}
