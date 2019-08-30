<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_productos', function (Blueprint $table) {
            $table->unsignedInteger('producto_id');
            $table->unsignedInteger('deposito_id');
            $table->decimal('cantidad', 8, 2);            
            $table->timestamps();

            $table->primary(['producto_id', 'deposito_id']);

            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('deposito_id')->references('id')->on('depositos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_productos');
    }
}
