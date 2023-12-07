<?php

namespace App\Helpers\Account;

use App\Helpers\Text\Translate;
use App\Models\Account;
use App\Models\Partner;
use \Illuminate\Http\Request;
use App\Models\AccountLogin;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Helpers\Base\Status;
use App\Helpers\Base\Date;
use App\Helpers\Base\Ip;
use App\Helpers\Address\AddressInterface;
use App\Models\PartnerSession;
use App\Models\Session;
use App\Helpers\Base\Tools;
use App\Models\AccountPartner;

class AccountInterface
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
     * @var Account|null
     */
    protected $accountJob = null;

    /**
     * @var Tools
     */
    protected $tools;

    /**
     * @var Partner|null
     */
    protected $currentPartner = null;

    public function __construct()
    {
        $this->translate = new Translate();
        $this->status = new Status();
        $this->date = new Date();
        $this->addressInterface = new AddressInterface();
        $this->tools = new Tools();
    }

    /**
     * @param Request $request
     * @return void
     */
    public function createAccountJobByPartner(Request $request)
    {
        if (!isset($request->all()["partner"]) || !isset($request->all()["partner"]["account"])) {
            throw new Exception("Parametros invalidos.");
        }

        $accountParams = $request->all()["partner"]["account"];

        if (is_null($this->getAccountJobsByEmail($accountParams[$this->translate->getEmail()]))) {
            $this->createAccountJob($accountParams);
        }

        $this->setAccountJob($accountParams);

        if (is_null($this->getAccountLogin())) {
            $this->createAccountJobLoggin($accountParams);
        }
    }

    /**
     * @param array $account
     * @return bool
     */
    public function createAccountJob(array $account){
        try {
            $this->validateEmail($account[$this->translate->getEmail()]);
            $Account = new Account();
            $Account->name = $account[$this->translate->getName()];
            $Account->email = $account[$this->translate->getEmail()];
            $Account->token = $this->tools->generate64B($account[$this->translate->getEmail()]);
            $Account->created_at = $this->date->getFullDate();
            $Account->updated_at = null;
            return $Account->save();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * @param string $email
     */
    private function validateEmail(string $email){
        $emailsAccoun = Account::select($this->translate->getId())->where($this->translate->getEmail(), $email)->get()->toArray();
        if (count($emailsAccoun) > 0) {
            throw new Exception($this->translate->getEmailAlready());
        }
    }

    /**
     * @param string $value
     * @return int|null
     */
    public function getAccountJobsByEmail(string $value){
        $accountJobs = Account::select($this->translate->getId())->where($this->translate->getEmail(), $value)->get()->toArray();
        if (count($accountJobs) > 0) {
            return $accountJobs[0][$this->translate->getId()];
        }else{
            return null;
        }
    }

    /**
     * @return int|null
     */
    public function getAccountLogin(){
        $accountLogin = AccountLogin::select($this->translate->getId())->where($this->translate->getIdAccount(), $this->accountJob->id)->get()->toArray();
        if (count($accountLogin) > 0) {
            return $accountLogin[0][$this->translate->getId()];
        }else{
            return null;
        }
    }

    /**
     * @param array $account
     * @return bool
     */
    public function createAccountJobLoggin(array $account){
        try {
            $AccountLogin = new AccountLogin();
            $AccountLogin->username = $account[$this->translate->getUsername()];
            $AccountLogin->password = $this->encriptionPawd($account[$this->translate->getPassword()]);
            $AccountLogin->status = $this->status->getEnable();
            $AccountLogin->id_account = $this->accountJob->id;
            $AccountLogin->created_at = $this->date->getFullDate();
            $AccountLogin->updated_at = null;
            return $AccountLogin->save();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * @param array $account
     * @return void
     */
    private function setAccountJob(array $account){
        $this->accountJob = Account::where($this->translate->getEmail(), $account[$this->translate->getEmail()])->
            where($this->translate->getToken(), $this->tools->generate64B($account[$this->translate->getEmail()]))->first();
    }

    /**
     * @return bool
     */
    public function setAccountPartnerRelation(){
        try {
            if (is_null($this->getAccountPartner($this->currentPartner->id, $this->accountJob->id))) {
                $accountPartner = new AccountPartner();
                $accountPartner->id_partner = $this->currentPartner->id;
                $accountPartner->id_account = $this->accountJob->id;
                $accountPartner->status = $this->status->getEnable();
                return $accountPartner->save();
            }else{
                throw new Exception($this->translate->getAccountRegister());
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new Exception($e->getMessage());
        }

        return false;
    }

    /**
     * @param int $id_partner
     * @param int $id_account
     * @return int|null
     */
    public function getAccountPartner(int $id_partner, int $id_account){
        $AccountPartner = AccountPartner::select($this->translate->getIdPartner())->where($this->translate->getIdPartner(), $id_partner)->
        where($this->translate->getIdAccount(), $id_account)->get()->toArray();
        if (count($AccountPartner) > 0) {
            return  $AccountPartner[0][$this->translate->getIdPartner()];
        }else{
            return null;
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    public function createAccountJobPartner(Request $request)
    {
        if (!isset($request->all()["partner"]) || !isset($request->all()["partner"]["account"])) {
            throw new Exception("Parametros invalidos.");
        }

        $partnerParams = $request->all()["partner"];
        $partnerParams["alias"] = $this->translate->camelCase($partnerParams[$this->translate->getName()]);
        $partnerParams["code"] = $this->translate->snakeCase($partnerParams[$this->translate->getName()]);
        $this->createPartner($partnerParams);
        $this->getCurrentAccountPartner($partnerParams["code"]);
    }

    /**
     * @param array $partner
     * @return bool
     */
    public function createPartner(array $partner){
        try {
            $this->validateDomainCode($partner["code"]);
            $Partner = new Partner();
            $Partner->alias = $partner["alias"];
            $Partner->code = $partner["code"];
            $Partner->name = $partner[$this->translate->getName()];
            $Partner->email = $partner[$this->translate->getEmail()];
            $Partner->telefono = $partner["telefono"];
            $Partner->token = $this->tools->generateToken($partner["code"]);
            $Partner->status = $this->status->getDisable();
            $Partner->id_account = $this->accountJob->id;
            $Partner->created_at = $this->date->getFullDate();
            $Partner->updated_at = null;
            return $Partner->save();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * @param string $code
     */
    private function validateDomainCode(string $code){
        $Partner = Partner::select($this->translate->getId())->where("code", $code)->get()->toArray();
        if (count($Partner) > 0) {
            throw new Exception($this->translate->getPartnerAlready());
        }
    }

    /**
     * @param string $code
     * @return void
     */
    private function getCurrentAccountPartner($code){
        $this->currentPartner = Partner::where($this->translate->getDomain(), $code)->first();
    }

    /**
     * @param string $email
     * @return bool
     */
    public function verifyEmailPartner(string $email)
    {
        if (
            count(
                Partner::select($this->translate->getId())->where($this->translate->getEmail(), $email)->get()->toArray()
            ) > 0
        ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param string $email
     * @return bool
     */
    public function verifyEmailAccount(string $email)
    {
        if (
            count(
                Account::select(
                    $this->translate->getId()
                )->where(
                    $this->translate->getEmail(),
                    $email
                )->get()->toArray()
            ) > 0
        ) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * @param string $password
     * @return string
     */
    private function encriptionPawd(string $password)
    {
        return hash_hmac($this->translate->getEncryptMethod(), $password, env($this->translate->getEncryptKey()));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function validateLogin(Request $request)
    {
        return $this->validateAccountLogin($request->all()[$this->translate->getUsername()], $request->all()[$this->translate->getPassword()], $request->ip());
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $ip
     * @return array
     */
    private function validateAccountLogin(string $username, string $password, string $ip)
    {
        $arrayUser = explode(
            $this->translate->getArroba(),
            $username
        );

        if (count($arrayUser) == 2) {
            if ($this->issetDomain($arrayUser[0])) {

                $response = $this->getByUsernameLogin($arrayUser[1]);

                if ($response != null) {
                    if ($response[0][$this->translate->getStatus()] == 1) {
                        if ($response[0][$this->translate->getPassword()] == $this->encriptionPawd($password)) {

                            $Account = $this->byAccountId(
                                $response[0][$this->translate->getIdAccount()]
                            );

                            if ($Account != null) {

                                $api_ip = new Ip($ip);

                                $this->setPartnerSession(
                                    $Account->accountPartner->id_partner,
                                    $this->setSession(
                                        $api_ip->validIp(),
                                        $this->addressInterface->createGeo(
                                            $api_ip->getGeo()
                                        )
                                    )
                                );

                                return $this->translate->messageLogin(true, 0, $Account->token);
                            } else {
                                return $this->translate->messageLogin(false, 6);
                            }
                        } else {
                            return $this->translate->messageLogin(false, 1);
                        }
                    } else {
                        return $this->translate->messageLogin(false, 2);
                    }
                } else {
                    return $this->translate->messageLogin(false, 3);
                }
            } else {
                return $this->translate->messageLogin(false, 4);
            }
        } else {
            return $this->translate->messageLogin(false, 5);
        }
    }

    /**
     * @param int|null $id_ip
     * @param int|null $id_localization
     * @return int|null
     */
    public function setSession(int|null $id_ip, int|null $id_localization)
    {
        try {
            $Session = new Session();
            $Session->date = $this->date->getFullDate();
            $Session->id_ip = $id_ip;
            $Session->id_localization = $id_localization;
            $Session->save();
            return $Session->id;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }

    /**
     * @param int|null $id_partner
     * @param int|null $id_session
     * @return int|null
     */
    public function setPartnerSession(int|null $id_partner, int|null $id_session)
    {
        try {
            $PartnerSession = new PartnerSession();
            $PartnerSession->id_partner = $id_partner;
            $PartnerSession->id_session = $id_session;
            $PartnerSession->status = $this->status->getEnable();
            $PartnerSession->save();
            return $PartnerSession->id;
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return null;
        }
    }

    /**
     * @param int $id
     * @return Account
     */
    public function byAccountId(int $id)
    {
        return Account::find($id);
    }

    /**
     * @param string $domain
     * @return bool
     */
    public function issetDomain(string $domain)
    {
        $Partner = Partner::select(
            $this->translate->getId()
        )->where(
            $this->translate->getDomain(),
            strtoupper($domain)
        )->get()->toArray();

        if (count($Partner) > 0) {
            return  true;
        } else {
            return false;
        }
    }

    /**
     * @param string $username
     * @return array|null
     */
    private function getByUsernameLogin(string $username)
    {
        $accountLogin = AccountLogin::select(
            $this->translate->getId(),
            $this->translate->getPassword(),
            $this->translate->getStatus(),
            $this->translate->getIdAccount()
        )->where($this->translate->getUsername(), $username)->get()->toArray();

        if (count($accountLogin) > 0) {
            return $accountLogin;
        } else {
            return null;
        }
    }
}
