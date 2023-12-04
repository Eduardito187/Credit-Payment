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
        Schema::create('partner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('alias');
            $table->string('code');
            $table->string('name');
            $table->string('email');
            $table->string('telefono');
            $table->string('token')->nullable();
            $table->boolean('status');
            $table->unsignedBigInteger('id_account')->nullable();
            $table->foreign('id_account')->references('id')->on('account')->onDelete('cascade');
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
        Schema::dropIfExists('partner', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_account');
        });
        Schema::dropIfExists('partner');
    }
};
