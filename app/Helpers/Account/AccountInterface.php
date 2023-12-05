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

    public function __construct()
    {
        $this->translate = new Translate();
        $this->status = new Status();
        $this->date = new Date();
        $this->addressInterface = new AddressInterface();
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
                Account::select($this->translate->getId())->where($this->translate->getEmail(), $email)->get()->toArray()
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
        } catch (Exception $th) {
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
        } catch (Exception $th) {
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
