<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalDolaresToRecibos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recibos', function (Blueprint $table) {
            $table->float('total_dolares', 12, 2)->default(0)->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recibos', function (Blueprint $table) {
            $table->dropColumn('total_dolares');
        });
    }
}
