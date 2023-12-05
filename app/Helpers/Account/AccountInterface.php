<?php

namespace App\Helpers\Account;

use App\Helpers\Text\Translate;
use App\Models\Account;
use App\Models\Partner;

class AccountInterface
{
    /**
     * @var Translate
     */
    protected $translate;

    public function __construct()
    {
        $this->translate = new Translate();
    }
    
    /**
     * @param string $email
     * @return bool
     */
    public function verifyEmailPartner(string $email){
        if (
            count(
                Partner::select($this->translate->getId())->where($this->translate->getEmail(), $email)->get()->toArray()
            ) > 0
        ) {
            return false;
        }else{
            return true;
        }
    }

    /**
     * @param string $email
     * @return bool
     */
    public function verifyEmailAccount(string $email){
        if (
            count(
                Account::select($this->translate->getId())->where($this->translate->getEmail(), $email)->get()->toArray()
            ) > 0
        ) {
            return false;
        }else{
            return true;
        }
    }
}
