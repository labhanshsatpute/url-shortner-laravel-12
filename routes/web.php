<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'viewLogin'])->name('view.login');
    Route::post('/login', [AuthController::class, 'handleLogin'])->name('handle.login');
});

Route::middleware('auth')->group(function () {

    Route::post('logout', function () {
        Auth::logout();
        return redirect()->route('view.login');
    })->name('handle.logout');

    Route::get('dashboard', [DashboardController::class, 'viewDasboard'])->name('view.dashboard');


});