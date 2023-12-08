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
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('num_pago');
            $table->decimal('monto', 10, 2);
            $table->boolean('mora');
            $table->string('fecha_pago')->nullable();
            $table->string('fecha_pagado')->nullable();
            $table->unsignedBigInteger('id_estado')->nullable();
            $table->foreign('id_estado')->references('id')->on('estados')->onDelete('cascade');
            $table->unsignedBigInteger('id_cobrador')->nullable();
            $table->foreign('id_cobrador')->references('id')->on('account')->onDelete('cascade');
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->foreign('id_customer')->references('id')->on('customer')->onDelete('cascade');
            $table->unsignedBigInteger('id_prestamo')->nullable();
            $table->foreign('id_prestamo')->references('id')->on('prestamos')->onDelete('cascade');
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
        Schema::dropIfExists('pagos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_estado');
            $table->dropConstrainedForeignId('id_cobrador');
            $table->dropConstrainedForeignId('id_customer');
            $table->dropConstrainedForeignId('id_prestamo');
        });
        Schema::dropIfExists('pagos');
    }
};