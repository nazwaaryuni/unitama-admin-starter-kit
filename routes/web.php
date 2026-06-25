<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Login
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');


//Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


//Setting
Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
Route::put('/setting/{setting}/update', [SettingController::class, 'update'])->name('setting.update');
Route::resource('/user', UserController::class);