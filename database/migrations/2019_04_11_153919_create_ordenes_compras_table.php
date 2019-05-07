<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenesComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_compras', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('anio_id'); // Ultimo Codigo por anio
            $table->unsignedInteger('anio_actual'); // Anio del codigo
            $table->unsignedInteger('proveedor_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('deposito_id')->nullable();
            $table->unsignedInteger('formapago_id')->nullable();
            $table->unsignedInteger('condicionpago_id')->nullable();

            // INICIO En caso de que sea para el cliente
            $table->unsignedInteger('cliente_id')->nullable();
            $table->unsignedInteger('vendedor_venta_id')->nullable();
            $table->unsignedInteger('vendedor_gestion_id')->nullable();
            // FIN En caso de que sea para el cliente

            $table->char('estado', 2)->default('PE'); //PE = Pendiente, AN = Anulado, CO = Confirmado, FI = Finalizado
            $table->char('tipo', 2)->default('PR'); //PR = Propia, CL = Cliente
            $table->decimal('total', 8, 2);
            $table->date('fecha');
            $table->string('numero_negocio')->nullable();
            $table->string('lugar_entrega')->nullable();
            $table->string('obs')->nullable();
            $table->timestamps();

            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('deposito_id')->references('id')->on('depositos');
            $table->foreign('formapago_id')->references('id')->on('forma_pagos');
            $table->foreign('condicionpago_id')->references('id')->on('condicion_pagos');
            
            // INICIO En caso de que sea para el cliente
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('vendedor_venta_id')->references('id')->on('vendedores');
            $table->foreign('vendedor_gestion_id')->references('id')->on('vendedores');
            // FIN En caso de que sea para el cliente
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_compras');
    }
}
