<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100);
            $table->mediumText('direccion');
            $table->string('correo_electronico')->unique();
            $table->string('telefono', 50);
            $table->unsignedInteger('tipo_documento');
            $table->string('numero_documento', 50)->unique();
            $table->char('estado', 1)->default('A');            
            $table->timestamps();

            $table->foreign('tipo_documento')->references('id')->on('tipos_documentos');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
