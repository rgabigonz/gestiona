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
            $table->unsignedInteger('proveedor_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('deposito_id');
            $table->unsignedInteger('formapago_id');
            $table->char('estado', 2)->default('PE'); //PE = Pendiente, PR = En proceso, AN = Anulado, FI = Finalizado
            $table->char('tipo', 2)->default('PR'); //PR = Propia, CL = Cliente
            $table->decimal('total', 8, 2);
            $table->date('fecha');            
            $table->timestamps();

            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('deposito_id')->references('id')->on('depositos');
            $table->foreign('formapago_id')->references('id')->on('forma_pagos');
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
