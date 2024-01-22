<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Config;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $config = new Config();
        $config->code = "maintenance_mode";
        $config->value = 0;
        $config->status = false;
        $config->created_at = date("Y-m-d H:i:s");
        $config->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Config::where("code", "maintenance_mode")->delete();
    }
};