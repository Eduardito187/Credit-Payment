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
        Schema::create('dia_pago', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pago')->nullable();
            $table->foreign('id_pago')->references('id')->on('pagos')->onDelete('cascade');
            $table->unsignedBigInteger('id_cobrador')->nullable();
            $table->foreign('id_cobrador')->references('id')->on('account')->onDelete('cascade');
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->foreign('id_customer')->references('id')->on('customer')->onDelete('cascade');
            $table->unsignedBigInteger('id_prestamo')->nullable();
            $table->foreign('id_prestamo')->references('id')->on('prestamos')->onDelete('cascade');
            $table->boolean('pagado');
            $table->boolean('mora');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dia_pago', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_pago');
            $table->dropConstrainedForeignId('id_cobrador');
            $table->dropConstrainedForeignId('id_customer');
            $table->dropConstrainedForeignId('id_prestamo');
        });
        Schema::dropIfExists('dia_pago');
    }
};