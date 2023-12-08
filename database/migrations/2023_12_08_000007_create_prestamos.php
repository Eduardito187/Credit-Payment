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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('monto_base', 10, 2);
            $table->decimal('monto_interes', 10, 2);
            $table->decimal('monto_total', 10, 2);
            $table->decimal('monto_pago', 10, 2);
            $table->boolean('mora');
            $table->string('fecha_prestamo')->nullable();
            $table->string('fecha_finalizacion')->nullable();
            $table->unsignedBigInteger('id_estado')->nullable();
            $table->foreign('id_estado')->references('id')->on('estados')->onDelete('cascade');
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->foreign('id_customer')->references('id')->on('customer')->onDelete('cascade');
            $table->unsignedBigInteger('id_plan_cuota')->nullable();
            $table->foreign('id_plan_cuota')->references('id')->on('plan_cuota')->onDelete('cascade');
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
        Schema::dropIfExists('prestamos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_estado');
            $table->dropConstrainedForeignId('id_customer');
            $table->dropConstrainedForeignId('id_plan_cuota');
        });
        Schema::dropIfExists('prestamos');
    }
};