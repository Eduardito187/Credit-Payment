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
        Schema::create('plan_cuota', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('monto_base', 10, 2);
            $table->decimal('monto_interes', 10, 2);
            $table->decimal('monto_total', 10, 2);
            $table->unsignedBigInteger('id_financiamiento')->nullable();
            $table->foreign('id_financiamiento')->references('id')->on('financiamientos')->onDelete('cascade');
            $table->unsignedBigInteger('id_interes')->nullable();
            $table->foreign('id_interes')->references('id')->on('intereses')->onDelete('cascade');
            $table->unsignedBigInteger('id_plazos')->nullable();
            $table->foreign('id_plazos')->references('id')->on('plazos')->onDelete('cascade');
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
        Schema::dropIfExists('plan_cuota', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_financiamiento');
            $table->dropConstrainedForeignId('id_interes');
            $table->dropConstrainedForeignId('id_plazos');
        });
        Schema::dropIfExists('plan_cuota');
    }
};