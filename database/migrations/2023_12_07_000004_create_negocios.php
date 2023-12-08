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
        Schema::create('negocios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('id_cargo_negocio')->nullable();
            $table->foreign('id_cargo_negocio')->references('id')->on('cargo_negocio')->onDelete('cascade');
            $table->unsignedBigInteger('id_rubro_negocio')->nullable();
            $table->foreign('id_rubro_negocio')->references('id')->on('rubro_negocio')->onDelete('cascade');
            $table->unsignedBigInteger('id_tipo_negocio')->nullable();
            $table->foreign('id_tipo_negocio')->references('id')->on('tipo_negocio')->onDelete('cascade');
            $table->unsignedBigInteger('id_address')->nullable();
            $table->foreign('id_address')->references('id')->on('address')->onDelete('cascade');
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
        Schema::dropIfExists('negocios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_negocio');
            $table->dropConstrainedForeignId('id_address');
        });
        Schema::dropIfExists('negocios');
    }
};