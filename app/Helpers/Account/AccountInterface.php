<?php

namespace App\Helpers\Account;

use App\Helpers\Text\Translate;
use App\Models\Account;
use App\Models\Partner;
use \Illuminate\Http\Request;
use App\Models\AccountLogin;
use Exception;
use App\Helpers\Base\Status;
use App\Helpers\Base\Date;
use App\Helpers\Base\Ip;
use App\Helpers\Address\AddressInterface;
use App\Models\PartnerSession;
use App\Models\Session;
use App\Helpers\Base\Tools;
use App\Models\AccountPartner;
use App\Helpers\TokenAccess;
use App\Models\Negocio;
use App\Models\TipoNegocio;
use App\Models\CargoNegocio;
use App\Models\RubroNegocio;
use App\Models\CustomerNegocio;
use App\Models\Customer;

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

    /**
     * @var TokenAccess
     */
    protected $tokenAccess = null;

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
    public function getNegocios()
    {
        $allNegocios = Negocio::all();

        $data = [];
        foreach ($allNegocios as $key => $negocio) {
            $data[] = $this->getNegocioArray($negocio);
        }

        return $data;
    }

    /**
     * @return array|null
     */
    public function getNegocioArray($negocio)
    {
        if (is_null($negocio)) {
            return null;
        }

        return array(
            "id" => $negocio->id,
            "name" => $negocio->name,
            "cargo" => $negocio->getCargoNegocio->toArray(),
            "rubro" => $negocio->getRubroNegocio->toArray(),
            "tipo" => $negocio->getTipoNegocio->toArray(),
            "address" => $this->getAddressArray($negocio->getAddress)
        );
    }

    /**
     * @return array|null
     */
    public function getAddressArray($address)
    {
        if (is_null($address)) {
            return null;
        }

        return array(
            "id" => $address->id,
            "municipality" => $this->getMunicipalityArray($address->getMunicipality),
            "country" => $this->getCountryArray($address->getCountry),
            "city" => $this->getCityArray($address->getCity),
            "address_extra" => $this->getAddressExtraArray($address->getAddressExtra),
            "localization" => $this->getLocalizationArray($address->getLocalization)
        );
    }

    /**
     * @return array|null
     */
    public function getMunicipalityArray($municipality)
    {
        if(is_null($municipality)) {
            return null;
        }

        return array(
            "id" => $municipality->id,
            "name" => $municipality->name,
            "city" => $municipality->getCity->toArray()
        );
    }

    /**
     * @return array|null
     */
    public function getAllCustomers()
    {
        $customers = Customer::all();
        $data = [];

        foreach ($customers as $customer) {
            $data[] = $this->getCustomerArray($customer);
        }

        return $data;
    }

    /**
     * @return array|null
     */
    public function getCustomerArray($customer)
    {
        if (is_null($customer)) {
            return null;
        }

        return array(
            "id" => $customer->id,
            "name" => $customer->name,
            "email" => $customer->email,
            "telefono" => $customer->telefono,
            "status" => $customer->status,
            "address" => $this->getAddressArray($customer->getAddress),
            "negocio" => $this->getCustomersNegocios($customer->getCustomerNegocio)
        );
    }

    /**
     * @param CustomerNegocio $customerNegocio
     * @return array
     */
    public function getCustomersNegocios($customerNegocio)
    {
        $data = [];

        foreach ($customerNegocio as $key => $item) {
            $data[] = $this->getNegocioArray($item->getNegocio);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getAllCustomerNegociosArray()
    {
        $customerNegocios = CustomerNegocio::all();
        $data = [];

        foreach ($customerNegocios as $item) {
            $data[] = array(
                "customer" => $this->getCustomerArray($item->getCustomer),
                "negocio" => $this->getNegocioArray($item->getNegocio)
            );
        }

        return $data;
    }

    /**
     * @param array $bodyData
     * @return array|null
     */
    public function getCustomerApi($bodyData)
    {
        $customer = Customer::find($bodyData["id_customer"]);

        if(is_null($customer)) {
            return null;
        }

        return $this->getCustomerArray($customer);
    }

    /**
     * @param array $bodyData
     * @return array|null
     */
    public function updateNegocioApi($bodyData)
    {
        $negocio = Negocio::find($bodyData["id_negocio"]);

        if(is_null($negocio)) {
            return null;
        }

        return $this->responseApi(
            $this->updateNameNegocio($negocio, $bodyData["name"])
        );
    }

    /**
     * @param array $bodyData
     * @return array|null
     */
    public function createNegocioCustomer($bodyData)
    {
        $status = false;

        try {
            $negocio = new Negocio();
            $negocio->name = $bodyData["name"];
            $negocio->id_cargo_negocio = null;
            $negocio->id_rubro_negocio = null;
            $negocio->id_tipo_negocio = null;
            $negocio->id_address = null;
            $negocio->created_at = $this->date->getFullDate();
            $negocio->updated_at = null;
            $status = $negocio->save();

            if ($status) {
                $status = $this->assingCustomerNegocio($bodyData["id_customer"], $negocio->id);
            }
        } catch (Exception $e) {
            $status = false;
        }

        return $this->responseApi($status);
    }

    /**
     * @param int $idCustomer
     * @param int $idNegocio
     * @return bool
     */
    public function assingCustomerNegocio($idCustomer, $idNegocio)
    {
        try {
            $customerNegocio = new CustomerNegocio();
            $customerNegocio->id_customer = $idCustomer;
            $customerNegocio->id_negocios = $idNegocio;
            return $customerNegocio->save();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param Negocio $negocio
     * @param string $name
     * @return bool
     */
    public function updateNameNegocio($negocio, $name){
        $negocio->name = $name;
        return $negocio->save();
    }

    /**
     * @param array $bodyData
     * @return array|null
     */
    public function createCustomerAccount($bodyData)
    {
        try {
            $Customer = new Customer();
            $Customer->name = $bodyData["name"];
            $Customer->email = $bodyData["email"];
            $Customer->telefono = $bodyData["telefono"];
            $Customer->id_address = null;
            $Customer->status = false;
            $Customer->created_at = $this->date->getFullDate();
            $Customer->updated_at = null;
            return $Customer->save();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @param array $bodyData
     * @return array|null
     */
    public function updateCustomerApi($bodyData)
    {
        $customer = Customer::find($bodyData["id_customer"]);

        if(is_null($customer)) {
            return null;
        }

        return $this->responseApi(
            $this->updateStatusCustomer($customer, $bodyData["status"])
        );
    }

    /**
     * @param Customer $customer
     * @param bool $status
     * @return bool
     */
    public function updateStatusCustomer($customer, $status){
        $customer->status = $status;
        return $customer->save();
    }

    /**
     * @param bool $status
     * @return array|null
     */
    public function responseApi($status)
    {
        return array(
            "status" => $status,
            "response" => $status
                ? $this->translate->getErrorQuery()
                : $this->translate->getSuccessQuery()
        );
    }

    /**
     * @return array|null
     */
    public function getCountryArray($country)
    {
        if(is_null($country)) {
            return null;
        }

        return $country->toArray();
    }

    /**
     * @return array|null
     */
    public function getCityArray($city)
    {
        if(is_null($city)) {
            return null;
        }

        return $city->toArray();
    }

    /**
     * @return array|null
     */
    public function getAddressExtraArray($addressExtra)
    {
        if (is_null($addressExtra)) {
            return null;
        }

        return $addressExtra->toArray();
    }

    /**
     * @return array|null
     */
    public function getLocalizationArray($localization)
    {
        if (is_null($localization)) {
            return null;
        }

        return $localization->toArray();
    }

    /**
     * @return array
     */
    public function getTipoNegocio()
    {
        return TipoNegocio::all()->toArray();
    }

    /**
     * @return array
     */
    public function getCargoNegocio()
    {
        return CargoNegocio::all()->toArray();
    }

    /**
     * @return array
     */
    public function getRubroNegocio()
    {
        return RubroNegocio::all()->toArray();
    }

    /**
     * @param string $token
     * @return array
     */
    public function currentAccountArray(string $token){
        $this->tokenAccess = new TokenAccess($token);
        $this->setAccountJobByToken($this->tokenAccess->getToken());

        if (!$this->accountJob) {
            throw new Exception($this->translate->getAccountNoExist());
        }

        return $this->requestAccount();
    }

    /**
     * @return array
     */
    public function requestAccount(){
        $Partner = $this->accountJob->accountPartner->Partner;
        return array(
            $this->translate->getId() => $this->accountJob->id,
            $this->translate->getName() => $this->accountJob->name,
            $this->translate->getEmail() => $this->accountJob->email,
            $this->translate->getIdPartner() => $Partner->id,
            "roles" => []
        );
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
        $accountParams["number_phone"] = $request->all()["partner"]["number_phone"];

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
    public function createAccountJob(array $account)
    {
        try {
            $this->validateEmail($account[$this->translate->getEmail()]);
            $Account = new Account();
            $Account->name = $account[$this->translate->getName()];
            $Account->email = $account[$this->translate->getEmail()];
            $Account->telefono = $account["number_phone"];
            $Account->token = $this->tools->generate64B($account[$this->translate->getEmail()]);
            $Account->status = $this->status->getDisable();
            $Account->created_at = $this->date->getFullDate();
            $Account->updated_at = null;
            return $Account->save();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $email
     */
    private function validateEmail(string $email)
    {
        $emailsAccoun = Account::select($this->translate->getId())->where($this->translate->getEmail(), $email)->get()->toArray();
        if (count($emailsAccoun) > 0) {
            throw new Exception($this->translate->getEmailAlready());
        }
    }

    /**
     * @param string $value
     * @return int|null
     */
    public function getAccountJobsByEmail(string $value)
    {
        $accountJobs = Account::select($this->translate->getId())->where($this->translate->getEmail(), $value)->get()->toArray();
        if (count($accountJobs) > 0) {
            return $accountJobs[0][$this->translate->getId()];
        } else {
            return null;
        }
    }

    /**
     * @return int|null
     */
    public function getAccountLogin()
    {
        $accountLogin = AccountLogin::select($this->translate->getId())->where($this->translate->getIdAccount(), $this->accountJob->id)->get()->toArray();
        if (count($accountLogin) > 0) {
            return $accountLogin[0][$this->translate->getId()];
        } else {
            return null;
        }
    }

    /**
     * @param array $account
     * @return bool
     */
    public function createAccountJobLoggin(array $account)
    {
        try {
            $AccountLogin = new AccountLogin();
            $AccountLogin->username = $account[$this->translate->getUsername()];
            $AccountLogin->password = $this->encriptionPawd($account[$this->translate->getPassword()]);
            $AccountLogin->status = $this->status->getDisable();
            $AccountLogin->id_account = $this->accountJob->id;
            $AccountLogin->created_at = $this->date->getFullDate();
            $AccountLogin->updated_at = null;
            return $AccountLogin->save();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param array $account
     * @return void
     */
    private function setAccountJob(array $account)
    {
        $this->accountJob = Account::where($this->translate->getEmail(), $account[$this->translate->getEmail()])->where($this->translate->getToken(), $this->tools->generate64B($account[$this->translate->getEmail()]))->first();
    }

    /**
     * @param string $tokent
     * @return void
     */
    private function setAccountJobByToken(string $token)
    {
        $this->accountJob = Account::where("token", $token)->first();
    }

    /**
     * @return bool
     */
    public function setAccountPartnerRelation()
    {
        try {
            if (is_null($this->getAccountPartner($this->currentPartner->id, $this->accountJob->id))) {
                $accountPartner = new AccountPartner();
                $accountPartner->id_partner = $this->currentPartner->id;
                $accountPartner->id_account = $this->accountJob->id;
                $accountPartner->status = $this->status->getDisable();
                return $accountPartner->save();
            } else {
                throw new Exception($this->translate->getAccountRegister());
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return false;
    }

    /**
     * @param int $id_partner
     * @param int $id_account
     * @return int|null
     */
    public function getAccountPartner(int $id_partner, int $id_account)
    {
        $AccountPartner = AccountPartner::select($this->translate->getIdPartner())->where($this->translate->getIdPartner(), $id_partner)->where($this->translate->getIdAccount(), $id_account)->get()->toArray();
        if (count($AccountPartner) > 0) {
            return  $AccountPartner[0][$this->translate->getIdPartner()];
        } else {
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
        $partnerParams["alias"] = strtoupper($this->translate->camelCase($partnerParams[$this->translate->getName()]));
        $partnerParams["code"] = $this->translate->snakeCase($partnerParams[$this->translate->getName()]);
        $this->createPartner($partnerParams);
        $this->getCurrentAccountPartner($partnerParams["code"]);
    }

    /**
     * @param array $partner
     * @return bool
     */
    public function createPartner(array $partner)
    {
        try {
            $this->validateDomainCode($partner["code"]);
            $Partner = new Partner();
            $Partner->alias = strtoupper($partner["alias"]);
            $Partner->code = $partner["code"];
            $Partner->name = $partner[$this->translate->getName()];
            $Partner->email = $partner[$this->translate->getEmail()];
            $Partner->telefono = $partner["number_phone"];
            $Partner->token = $this->tools->generateToken($partner["code"]);
            $Partner->status = $this->status->getDisable();
            $Partner->id_account = $this->accountJob->id;
            $Partner->created_at = $this->date->getFullDate();
            $Partner->updated_at = null;
            return $Partner->save();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $code
     */
    private function validateDomainCode(string $code)
    {
        $Partner = Partner::select($this->translate->getId())->where("code", $code)->get()->toArray();
        if (count($Partner) > 0) {
            throw new Exception($this->translate->getPartnerAlready());
        }
    }

    /**
     * @param string $code
     * @return void
     */
    private function getCurrentAccountPartner($code)
    {
        $this->currentPartner = Partner::where("code", $code)->first();
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
                                
                                if ($Account->accountPartner->Partner->status == $this->status->getEnable()) {
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
                                }else{
                                    return $this->translate->messageLogin(false, 7);
                                }
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
            "alias",
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
