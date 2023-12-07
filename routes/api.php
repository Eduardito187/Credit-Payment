<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CustomValidateToken;
use App\Http\Controllers\Api\Account\Create as ControllerCreateAccount;

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

    /*
    Route::controller(Register::class)->group(function(){
        Route::post('register/account', 'store');
        Route::get('account/show/{id}', 'show');
        Route::patch('account/edit/{id}', 'update');
        Route::patch('account/updatePassword/{id}', 'updatePassword');
        Route::patch('account/changeStatus/{id}', 'changeStatus');
    });*/
});