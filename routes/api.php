<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/account', [AccountController::class, 'create']);
Route::get('/account/{account_number}', [AccountController::class, 'getByNumber']);
Route::post('/transaction', [TransactionController::class, 'store']);