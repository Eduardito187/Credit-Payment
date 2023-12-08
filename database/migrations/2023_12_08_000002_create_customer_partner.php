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
        Schema::create('customer_partner', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->foreign('id_customer')->references('id')->on('customer')->onDelete('cascade');
            $table->unsignedBigInteger('id_partner')->nullable();
            $table->foreign('id_partner')->references('id')->on('partner')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_partner', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_customer');
            $table->dropConstrainedForeignId('id_partner');
        });
        Schema::dropIfExists('customer_partner');
    }
};
