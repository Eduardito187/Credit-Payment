<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Helpers\MailCode;
use App\Helpers\Account\AccountInterface;
use App\Helpers\Text\Translate;
use App\Helpers\Base\Status;
use App\Helpers\Prestamos\PrestamosInterface;

class Prestamos extends Controller
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

    /**
     * @var PrestamosInterface
     */
    protected $prestamosInterface;

    public function __construct()
    {
        $this->accountInterface = new AccountInterface();
        $this->translate = new Translate();
        $this->status = new Status();
        $this->prestamosInterface = new PrestamosInterface();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPrestamos(Request $request)
    {
        return response()->json(
            $this->prestamosInterface->getAllPrestamos()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPlanesCuotas(Request $request)
    {
        return response()->json(
            $this->prestamosInterface->getAllPlanesCuotas()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPlazos(Request $request)
    {
        return response()->json(
            $this->prestamosInterface->getAllPlazos()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFinanciamientos(Request $request)
    {
        return response()->json(
            $this->prestamosInterface->getAllFinanciamiento()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getIntereses(Request $request)
    {
        return response()->json(
            $this->prestamosInterface->getAllIntereses()
        );
    }
}
