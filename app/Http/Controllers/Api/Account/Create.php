<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Helpers\MailCode;
use App\Helpers\Account\AccountInterface;

class Create extends Controller
{
    /**
     * @var AccountInterface
     */
    protected $accountInterface;

    public function __construct(
    ) {
        $this->accountInterface = new AccountInterface();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyMail(Request $request)
    {
        $state = null;
        $status = null;
        try {
            if (!is_null($request->all()["email"]) && !is_null($request->all()["code"]) && !is_null($request->all()["type"])) {
                $email = null;
                if ($request->all()["type"] == "partner") {
                    $email = $this->accountInterface->verifyEmailPartner($request->all()["email"]);
                }else if ($request->all()["type"] == "account") {
                    $email = $this->accountInterface->verifyEmailAccount($request->all()["email"]);
                }
                if ($email == true) {
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
                $status = 'verify email => '.($email ? 'Si':'No');
            }else{
                $state = false;
            }
        } catch (Exception $th) {
            $state = null;
        }
        $response = array("status" => $state, "estatus" => $status);
        return response()->json($response);
    }
}
