<?php

namespace App\Helpers\Base;

use App\Models\Config;
use App\Models\IntegrationsAPI;
use App\Models\Ip;
use App\Models\Localization;
use App\Models\RestrictIp;
use Illuminate\Support\Facades\Hash;
use App\Models\Migrations;

class Tools
{
    public function __construct()
    {
        //
    }

    /**
     * @return array
     */
    public function getAllRestrictIp()
    {
        $restrictIp = RestrictIp::all();

        return $restrictIp->toArray();
    }

    /**
     * @return array
     */
    public function getAllMigrations()
    {
        $migrations = Migrations::all();

        return $migrations->toArray();
    }

    /**
     * @return array
     */
    public function getAllLocalization()
    {
        $localization = Localization::all();

        return $localization->toArray();
    }

    /**
     * @return array
     */
    public function getAllIp()
    {
        $ip = Ip::all();

        return $ip->toArray();
    }

    /**
     * @return array
     */
    public function getAllIntegrationApi()
    {
        $integrationsAPI = IntegrationsAPI::all();

        return $integrationsAPI->toArray();
    }

    /**
     * @return array
     */
    public function getAllConfig()
    {
        $configSystem = Config::all();

        return $configSystem->toArray();
    }

    /**
     * @param string|int|float value
     * @return string
     */
    public function generate64B(string|int|float $value)
    {
        return base64_encode($value);
    }

    /**
     * @param string $value
     * @return string
     */
    public function generateToken(string $value){
        return Hash::make($value, [
            "rounds" => 12,
        ]);
    }
}