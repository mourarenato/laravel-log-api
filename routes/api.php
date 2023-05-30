<?php

use App\Http\Controllers\ApiGatewayLogsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('signin', [UserController::class, 'signin']);
Route::post('signup', [UserController::class, 'signup']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('signout', [UserController::class, 'signout']);
    Route::post('logs/process', [ApiGatewayLogsController::class, 'processLogFile']);
    Route::post('logs/export/requestsByService', [ApiGatewayLogsController::class, 'exportReportOfRequestsByService']);
    Route::post('logs/export/requestsByConsumer', [ApiGatewayLogsController::class, 'exportReportOfRequestsByConsumer']);
    Route::post('logs/export/averageTime', [ApiGatewayLogsController::class, 'exportReportOfAverageTimeByService']);
});
