<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planes_pago', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pago')->nullable();
            $table->foreign('id_pago')->references('id')->on('pagos')->onDelete('cascade');
            $table->unsignedBigInteger('id_prestamo')->nullable();
            $table->foreign('id_prestamo')->references('id')->on('prestamos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planes_pago', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_pago');
            $table->dropConstrainedForeignId('id_prestamo');
        });
        Schema::dropIfExists('planes_pago');
    }
};