<?php

namespace App\Helpers\Address;

use App\Helpers\Text\Translate;
use \Illuminate\Http\Request;
use Exception;
use App\Helpers\Base\Status;
use App\Helpers\Base\Date;
use App\Models\Localization;

class AddressInterface
{
    /**
     * @var Translate
     */
    protected $translate;

    /**
     * @var Status
     */
    protected $status;

    /**
     * @var Date
     */
    protected $date;

    public function __construct()
    {
        $this->translate = new Translate();
        $this->status = new Status();
        $this->date = new Date();
    }
    
    /**
     * @param array $geo
     * @return int|null
     */
    public function createGeo(array $geo){
        try {
            $Localization = new Localization();
            $Localization->latitud = $geo[$this->translate->getLatitude()];
            $Localization->longitud = $geo[$this->translate->getLongitude()];
            $Localization->save();
            return $Localization->id;
        } catch (Exception $th) {
            return 0;
        }
    }
}
