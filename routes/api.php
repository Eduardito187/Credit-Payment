<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CustomValidateToken;
use App\Http\Controllers\Api\Account\Create as ControllerCreateAccount;
use App\Http\Controllers\Api\Account\Login as ControllerLogin;
use App\Http\Controllers\Api\Account\Customer as ControllerCustomer;
use App\Http\Controllers\Api\Account\Negocio as ControllerNegocio;
use App\Http\Controllers\Api\Account\Restore as ControllerRestore;
use App\Http\Controllers\Api\Account\Prestamos as ControllerPrestamos;
use App\Http\Controllers\Api\System\Tools as ControllerTools;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//params in api
// / => index <= get
// / => store <= post
// /show/{id} => show <= get
// /{id} => update <= patch
// /{id} => destroy <= delete

Route::middleware([CustomValidateToken::class])->group(function () {
    Route::controller(ControllerCreateAccount::class)->group(function(){
        Route::post('account/verifyMail', 'verifyMail');
        Route::post('account/partner/register', 'createPartner');
        Route::post('account/jobs/register', 'createJob');
    });

    Route::controller(ControllerLogin::class)->group(function(){
        Route::post('account/validateLogin', 'validateLogin');
        Route::post('account/getCurrentAccount', 'getCurrentAccount');
    });

    Route::controller(ControllerCustomer::class)->group(function(){
        Route::post('customer/createCustomer', 'createCustomer');
        Route::get('customer/getCustomersList', 'getCustomersList');
        Route::post('customer/getCustomer', 'getCustomer');
        Route::post('customer/changeStatusCustomer', 'changeStatusCustomer');
    });

    Route::controller(ControllerNegocio::class)->group(function(){
        Route::post('negocio/createNegocio', 'createNegocio');
        Route::post('negocio/getNegociosCustomer', 'getNegociosCustomer');
        Route::post('negocio/updateNegocio', 'updateNegocio');
        Route::post('negocio/getCargosNegocio', 'getCargosNegocio');
        Route::post('negocio/getRubroNegocio', 'getRubroNegocio');
        Route::post('negocio/getTipoNegocio', 'getTipoNegocio');
        Route::post('negocio/getNegociosList', 'getNegociosList');
    });

    Route::controller(ControllerRestore::class)->group(function(){
        Route::post('account/restorePassword', 'restorePassword');
    });

    Route::controller(ControllerPrestamos::class)->group(function(){
        Route::get('prestamos/getPlazos', 'getPlazos');
        Route::get('prestamos/getFinanciamientos', 'getFinanciamientos');
        Route::get('prestamos/getIntereses', 'getIntereses');
        Route::get('prestamos/getPlanesCuotas', 'getPlanesCuotas');
    });

    Route::controller(ControllerTools::class)->group(function(){
        Route::get('system/getRestrictIp', 'getRestrictIp');
        Route::get('system/getMigrations', 'getMigrations');
        Route::get('system/getLocalization', 'getLocalization');
        Route::get('system/getIp', 'getIp');
        Route::get('system/getIntegrationApi', 'getIntegrationApi');
        Route::get('system/getConfig', 'getConfig');
    });
    /*
    Route::controller(Register::class)->group(function(){
        Route::post('register/account', 'store');
        Route::get('account/show/{id}', 'show');
        Route::patch('account/edit/{id}', 'update');
        Route::patch('account/updatePassword/{id}', 'updatePassword');
        Route::patch('account/changeStatus/{id}', 'changeStatus');
    });*/
});