<?php

namespace App\Http\Controllers\Api\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Text\Translate;
use App\Helpers\Base\Status;
use App\Helpers\Base\Tools as HelperTools;

class Tools extends Controller
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
     * @var HelperTools
     */
    protected $tools;

    public function __construct()
    {
        $this->translate = new Translate();
        $this->status = new Status();
        $this->tools = new HelperTools();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRestrictIp(Request $request)
    {
        return response()->json(
            $this->tools->getAllRestrictIp()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getMigrations(Request $request)
    {
        return response()->json(
            $this->tools->getAllMigrations()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getLocalization(Request $request)
    {
        return response()->json(
            $this->tools->getAllLocalization()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getIp(Request $request)
    {
        return response()->json(
            $this->tools->getAllIp()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getIntegrationApi(Request $request)
    {
        return response()->json(
            $this->tools->getAllIntegrationApi()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getConfig(Request $request)
    {
        return response()->json(
            $this->tools->getAllConfig()
        );
    }
}
