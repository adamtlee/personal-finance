<?php

use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\ExpenseController;
use App\Http\Controllers\Api\V1\InstitutionController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\UserController;
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

// API v1 routes
Route::prefix('v1')->group(function () {
    // Public routes (no authentication required for now)
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/institutions', [InstitutionController::class, 'index']);
    Route::get('/accounts', [AccountController::class, 'index']);
    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::get('/expenses', [ExpenseController::class, 'index']);
    
    // Individual resource routes
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/institutions/{id}', [InstitutionController::class, 'show']);
    Route::get('/accounts/{id}', [AccountController::class, 'show']);
    Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show']);
    Route::get('/expenses/{id}', [ExpenseController::class, 'show']);
});
