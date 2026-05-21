<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminPasarController;
use App\Http\Controllers\MapController;

// 1. SEKTOR PUBLIC (Guest)
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login-proses', [AuthController::class, 'login'])->name('login.proses'); 
});

// 2. SEKTOR PROTECTED (Wajib Login)
Route::middleware(['auth'])->group(function () {
    
    // Fallback untuk /home agar tidak 404
    Route::get('/home', function() {
        return redirect()->route('maps.index');
    });

    Route::get('/beranda', function() {
        return redirect()->route('maps.index');
    });
    
    // HALAMAN UTAMA
    Route::get('/maps', [MapController::class, 'index'])->name('maps.index');
    
    // MANAGEMENT DATA PASAR
    Route::resource('pasars', AdminPasarController::class);
    Route::get('/upload-pasar', [PasarController::class, 'halamanUpload'])->name('pasar.upload');
    Route::post('/proses-upload', [PasarController::class, 'prosesUpload'])->name('pasar.proses');
    
    // TRANSAKSI
    Route::resource('transaksis', TransaksiController::class);

    // LOGOUT
    // Pastikan di AuthController fungsi logout-nya juga ada
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});