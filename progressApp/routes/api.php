<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\LogController;
use \App\Http\Controllers\ActivityController;
use \App\Http\Controllers\BodyParameterController;

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

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth')->delete('unregister', [AuthController::class, 'unregister']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth')->post('logout', [AuthController::class, 'logout']);

    Route::middleware('auth')->get('me', [UserController::class, 'me']);
    Route::middleware('auth')->put('me/update', [UserController::class, 'update']);

    Route::middleware('auth')->resource('log', LogController::class);
    Route::middleware('auth')->get('bodyparamlogs', [LogController::class, 'getBodyParamLog']);
    Route::middleware('auth')->get('activitylogs', [LogController::class, 'getActvityLog']);
    Route::middleware('auth')->resource('activities', ActivityController::class);
    Route::middleware('auth')->resource('bodyparameters', BodyParameterController::class);
});
