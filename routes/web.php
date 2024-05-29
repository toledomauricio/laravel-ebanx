<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::post('/conta', [AccountController::class, 'create']);
    Route::get('/conta/{numero_conta}', [AccountController::class, 'getByNumber']);
});