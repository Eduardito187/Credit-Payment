<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Account\AccountInterface;
use App\Helpers\Text\Translate;
use App\Helpers\Base\Status;
use Exception;

class Login extends Controller
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
    public function validateLogin(Request $request)
    {
        return response()->json($this->accountInterface->validateLogin($request));
    }

    public function getCurrentAccount(Request $request)
    {
        $response = null;

        try {
            $response = $this->translate->getResponseApi(
                $this->accountInterface->currentAccountArray($request->header($this->translate->getAuthorization())),
                $this->translate->getAccountResponse()
            );
        } catch (Exception $e) {
            $response = $this->translate->getResponseApi(null, $e->getMessage());
        }

        return response()->json($response);
    }
}
