<?php

namespace App\Helpers\Base;

use Illuminate\Support\Facades\Hash;

class Tools
{
    public function __construct()
    {
        //
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