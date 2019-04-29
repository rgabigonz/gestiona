<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100);
            $table->mediumText('descripcion')->nullable();
            $table->decimal('precio', 8, 2)->default(0);
            $table->decimal('stk_min', 8, 2)->default(0);
            $table->decimal('stk_max', 8, 2)->default(0);
            $table->decimal('stk_actual', 8, 2)->default(0);
            $table->unsignedInteger('tipo_producto');
            $table->char('estado', 1)->default('A');
            $table->timestamps();

            $table->foreign('tipo_producto')->references('id')->on('tipo_productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
