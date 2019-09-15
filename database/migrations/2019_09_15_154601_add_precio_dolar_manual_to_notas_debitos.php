<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrecioDolarManualToNotasDebitos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_debitos', function (Blueprint $table) {
            $table->decimal('precio_dolar_manual', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_debitos', function (Blueprint $table) {
            $table->dropColumn('precio_dolar_manual');
        });
    }
}
