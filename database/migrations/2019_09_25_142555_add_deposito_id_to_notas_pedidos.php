<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepositoIdToNotasPedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_pedidos', function (Blueprint $table) {
            $table->unsignedInteger('deposito_id')->nullable();
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
        Schema::table('notas_pedidos', function (Blueprint $table) {
            $table->dropColumn('deposito_id');
        });
    }
}
