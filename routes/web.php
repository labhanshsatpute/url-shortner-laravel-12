<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ShortUrlController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'viewLogin'])->name('view.login');
    Route::post('/login', [AuthController::class, 'handleLogin'])->name('handle.login');
    Route::post('/invite/register/{token}', [AuthController::class, 'handleRegisterFromInvite'])->name('handle.register.from.invite');
});

Route::get('/invite/register/{token}', [AuthController::class, 'viewInvite'])->name('view.invite');

Route::get('/s/{short_url_code}', [ShortUrlController::class, 'checkShortUrl'])->name('check.short-url');

Route::middleware('auth')->group(function () {

    Route::post('logout', function () {
        Auth::logout();
        return redirect()->route('view.login');
    })->name('handle.logout');

    Route::get('dashboard', [DashboardController::class, 'viewDasboard'])->name('view.dashboard');

    Route::prefix('company')->controller(CompanyController::class)->group(function () {
        Route::get('/', 'viewAllCompanies')->name('view.all.companies');
        Route::post('/create', 'handleCreateCompany')->name('handle.create.company');
    });

    Route::prefix('users')->controller(InviteController::class)->group(function () {
        Route::get('/', 'viewAllUsers')->name('view.all.users');
        Route::post('/create', 'handleCreateInvite')->name('handle.create.invite');
    });

    Route::prefix('short-url')->controller(ShortUrlController::class)->group(function () {
        Route::get('/', 'viewAllShortUrl')->name('view.all.shorturl');
        Route::post('/create', 'handleCreateShortUrl')->name('handle.create.shorturl');
    });

    Route::prefix('invite/request')->controller(InviteController::class)->group(function () {
        Route::get('/{token}', 'viewInviteRequest')->name('view.invite.request');
        Route::post('/{token}', 'handleInviteRequest')->name('handle.invite.request');
    });

});