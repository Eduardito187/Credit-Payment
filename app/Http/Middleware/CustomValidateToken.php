<?php

namespace App\Http\Middleware;

use \Closure;
use \Illuminate\Http\Request;
use App\Helpers\TokenAccess;
use App\Helpers\Text\Translate;
use App\Helpers\Base\Status;
use App\Helpers\Base\Ip;
use \Illuminate\Http\Response;
use \Illuminate\Http\RedirectResponse;

class CustomValidateToken
{
    const ERROR_402 = 402;
    const ERROR_404 = 404;
    /**
     * @var Translate
     */
    protected $translate;
    /**
     * @var Status
     */
    protected $status;
    /**
     * @var Ip
     */
    protected $Ip;

    public function __construct()
    {
        $this->translate   = new Translate();
        $this->status = new Status();
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->Ip = new Ip($request->ip());
        $this->Ip->validIp();
        if ($this->Ip->validRestrict() && $request->header($this->translate->getAuthorization()) != null) {
            $tokenAccess = new TokenAccess($request->header($this->translate->getAuthorization()));
            if ($tokenAccess->validateAPI() == $this->status->getEnable()) {
                return $next($request);
            } else {
                return abort(self::ERROR_402, $this->translate->getTokenDecline());
            }
        } else {
            return abort(self::ERROR_404, $this->translate->getAccessDecline());
        }
    }
}
