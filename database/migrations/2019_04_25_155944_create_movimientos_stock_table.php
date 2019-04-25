<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos_stock', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('producto_id');
            $table->unsignedInteger('deposito_id');
            $table->unsignedInteger('user_id');
            $table->date('fecha');
            $table->decimal('cantidad', 8, 2);
            $table->mediumText('descripcion');
            $table->char('tipo', 1)->default('I'); //I = Ingreso, E = Egreso
            $table->char('tipo_documento', 2)->default('NP'); //NP = NP Proveedor, NV = NV Cliente
            $table->unsignedInteger('documento_id'); //Codigo NP - NV
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('deposito_id')->references('id')->on('depositos');
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
        Schema::dropIfExists('movimientos_stock');
    }
}
