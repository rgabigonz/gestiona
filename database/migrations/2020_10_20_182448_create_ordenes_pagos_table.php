<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenesPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sucursal_id'); 
            $table->unsignedInteger('proveedor_id');
            $table->unsignedInteger('user_id');
            $table->char('estado', 2)->default('PE'); //PE = Pendiente, AN = Anulado, CO = Confirmado
            $table->decimal('total', 8, 2);
            $table->date('fecha');
            $table->string('obs')->nullable();
            $table->timestamps();

            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
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
        Schema::dropIfExists('ordenes_pagos');
    }
}
