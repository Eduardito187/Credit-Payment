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
        Schema::create('customer_negocio', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->foreign('id_customer')->references('id')->on('customer')->onDelete('cascade');
            $table->unsignedBigInteger('id_negocios')->nullable();
            $table->foreign('id_negocios')->references('id')->on('negocios')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_negocio', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_customer');
            $table->dropConstrainedForeignId('id_negocios');
        });
        Schema::dropIfExists('customer_negocio');
    }
};