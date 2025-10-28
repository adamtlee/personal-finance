<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstitutionExportController;
use App\Http\Controllers\AccountExportController;
use App\Http\Controllers\SubscriptionExportController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/admin/institutions/export', [InstitutionExportController::class, 'export'])->name('institutions.export');
Route::get('/admin/accounts/export', [AccountExportController::class, 'export'])->name('accounts.export');
Route::get('/admin/subscriptions/export', [SubscriptionExportController::class, 'export'])->name('subscriptions.export');
