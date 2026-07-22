<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressLookupController;


Route::get('/', function () {
    return view('home');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/comenzi/adauga', [OrderController::class, 'create'])->middleware('auth');
Route::post('/comenzi', [OrderController::class, 'store'])->middleware('auth');
Route::get('/comenzi/{id}', [OrderController::class, 'show'])->name('orders.show');

// AJAX: cauta codul postal pentru formularul de comanda
Route::get('/cauta/cod-postal', [AddressLookupController::class, 'codPostal'])->middleware('auth');
Route::get('/cauta/strazi', [AddressLookupController::class, 'strazi'])->middleware('auth');
