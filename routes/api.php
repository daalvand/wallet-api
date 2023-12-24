<?php

use App\Http\Controllers\WalletController;
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


Route::prefix('wallet')->name('wallet.')
    ->controller(WalletController::class)->group(function () {
        Route::get('balance/{user}', 'showBalance')->name('balance');
        Route::post('deposit/{user}', 'depositMoney')->name('deposit');
    });

