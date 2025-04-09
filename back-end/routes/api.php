<?php

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/accounts', [AccountController::class, 'index']);
Route::get('/account/{id}', [AccountController::class, 'detail']);
Route::post('/account', [AccountController::class, 'create']);
Route::put('/account/{id}', [AccountController::class, 'update']);
Route::delete('/account/{id}', [AccountController::class, 'delete']);
