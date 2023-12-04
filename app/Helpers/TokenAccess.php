<?php

namespace App\Helpers;

use App\Models\Account as ModelAccount;
use App\Models\Partner as ModelPartner;
use Illuminate\Support\Facades\Log;
use App\Helpers\Text\Translate;

class TokenAccess
{
    /**
     * @var Translate
     */
    protected $translate;
    /**
     * @var string
     */
    protected $token;

    /**
     * @param string $token
     */
    public function __construct(
        string $token
    ) {
        $this->token = $token;
        $this->translate = new Translate();
    }

    /**
     * @return bool
     */
    public function validateAPI()
    {
        $validatePartner = ModelPartner::select($this->translate->getId())->where($this->translate->getToken(), $this->getToken())->get()->toArray();
        if (count($validatePartner) == 0) {
            return $this->getTokenAccount();
        } else {
            return true;
        }
    }

    public function getToken()
    {
        $token = explode($this->translate->getSpace(), $this->token);
        if (count($token) == 2) {
            return $token[1];
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    private function getTokenAccount()
    {
        $validateAccount = ModelAccount::select($this->translate->getId())->where($this->translate->getToken(), $this->getToken())->get()->toArray();
        if (count($validateAccount) == 0) {
            return false;
        } else {
            return true;
        }
    }
}
