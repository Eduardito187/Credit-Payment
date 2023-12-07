<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Helpers\MailCode;
use App\Helpers\Account\AccountInterface;
use App\Helpers\Base\Ip;
use Illuminate\Support\Facades\Log;
use App\Helpers\Text\Translate;
use App\Helpers\Base\Status;

class Create extends Controller
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

    public function __construct() {
        $this->accountInterface = new AccountInterface();
        $this->translate = new Translate();
        $this->status = new Status();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyMail(Request $request)
    {
        $state = null;

        try {
            if (!is_null($request->all()["email"]) && !is_null($request->all()["code"]) && !is_null($request->all()["type"])) {

                $email = null;

                if ($request->all()["type"] == "partner") {
                    $email = $this->accountInterface->verifyEmailPartner($request->all()["email"]);
                }else if ($request->all()["type"] == "account") {
                    $email = $this->accountInterface->verifyEmailAccount($request->all()["email"]);
                }

                if ($email) {
                    $newEmail = new MailCode($request->all()["email"], "Código de verificación", $request->all()["code"]);
                    $state = $newEmail->createMail();
                }else{
                    if (!$request->all()["restore"]) {
                        $state = false;
                    }else{
                        $newEmail = new MailCode($request->all()["email"], "Código de restauración", $request->all()["code"]);
                        $state = $newEmail->createMail();
                    }
                }
            }else{
                $state = false;
                
            }
        } catch (Exception $th) {
            $state = null;
        }

        $response = array("status" => $state);
        return response()->json($response);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createPartner(Request $request)
    {
        $response = null;

        try {
            $this->accountInterface->createAccountJobByPartner($request);
            $this->accountInterface->createAccountJobPartner($request);
            $this->accountInterface->setAccountPartnerRelation();
            $response = $this->translate->getResponseApi($this->status->getEnable(), $this->translate->getAddSuccess());
        } catch (\Throwable $th) {
            $response = $this->translate->getResponseApi($this->status->getDisable(), $th->getMessage());
        }

        //$this->translate;
        return response()->json($response);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createJob(Request $request)
    {
        $response = null;

        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

        //$this->translate;
        return response()->json($response);
    }
}
