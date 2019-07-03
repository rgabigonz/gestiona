<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCuitToVendedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendedores', function (Blueprint $table) {
            $table->unsignedInteger('tipo_documento')->nullable();
            $table->string('numero_documento', 50)->unique()->nullable();

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
        Schema::table('vendedores', function (Blueprint $table) {
            $table->dropColumn('tipo_documento');
            $table->dropColumn('numero_documento');

            $table->dropForeign('vendedores_tipos_documentos_foreign');
        });
    }
}
