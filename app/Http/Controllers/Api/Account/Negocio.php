<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Helpers\MailCode;
use App\Helpers\Account\AccountInterface;
use App\Helpers\Text\Translate;
use App\Helpers\Base\Status;

class Negocio extends Controller
{
    /**
     * @var AccountInterface
     */
    protected $accountInterface;

    /**
     * @var Translate
     */
    protected $translate;

    /**
     * @var Status
     */
    protected $status;

    public function __construct()
    {
        $this->accountInterface = new AccountInterface();
        $this->translate = new Translate();
        $this->status = new Status();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createNegocio(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNegociosCustomer(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateNegocio(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCargosNegocio(Request $request)
    {
        return response()->json(
            $this->accountInterface->getCargoNegocio()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRubroNegocio(Request $request)
    {
        return response()->json(
            $this->accountInterface->getRubroNegocio()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTipoNegocio(Request $request)
    {
        return response()->json(
            $this->accountInterface->getTipoNegocio()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNegociosList(Request $request)
    {
        //
    }
}
