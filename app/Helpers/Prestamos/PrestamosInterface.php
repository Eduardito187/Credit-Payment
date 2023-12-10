<?php

namespace App\Helpers\Prestamos;

use App\Helpers\Text\Translate;
use \Illuminate\Http\Request;
use Exception;
use App\Helpers\Base\Status;
use App\Helpers\Base\Date;
use App\Helpers\Address\AddressInterface;
use App\Helpers\Base\Tools;
use App\Models\Financiamientos;
use App\Models\Intereses;
use App\Models\Plazos;

class PrestamosInterface
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

    /**
     * @var AddressInterface
     */
    protected $addressInterface;

    /**
     * @var Tools
     */
    protected $tools;

    public function __construct()
    {
        $this->translate = new Translate();
        $this->status = new Status();
        $this->date = new Date();
        $this->addressInterface = new AddressInterface();
        $this->tools = new Tools();
    }

    /**
     * @return array
     */
    public function getAllIntereses()
    {
        $allIntereses = Intereses::all();

        return $allIntereses->toArray();
    }

    /**
     * @return array
     */
    public function getAllPlazos()
    {
        $allPlazos = Plazos::all();

        return $allPlazos->toArray();
    }

    /**
     * @return array
     */
    public function getAllFinanciamiento()
    {
        $data = [];
        $allFinanciamientos = Financiamientos::all();

        foreach ($allFinanciamientos as $key => $financiamiento) {
            $data[] = $this->getFinanciamientoArray($financiamiento);
        }

        return $data;
    }

    /**
     * @param Financiamientos $financiamiento
     * @return array|null
     */
    public function getFinanciamientoArray($financiamiento)
    {
        if (is_null($financiamiento)) {
            return null;
        }

        return array(
            "id" => $financiamiento->id,
            "name" => $financiamiento->name,
            "code" => $financiamiento->code,
            "value" => $financiamiento->value,
            "status" => $financiamiento->status
        );
    }
}
