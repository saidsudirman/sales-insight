<?php

use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\UkuranController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::user()->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        }
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('admin.dashboard');
            })->name('dashboard');

            Route::patch('/kategoris/{kategori}/toggle-status', [
                KategoriController::class,
                'toggleStatus'
            ])->name('kategoris.toggle-status');

            Route::resource('kategoris', KategoriController::class)
                ->except(['show']);
                Route::patch('/ukurans/{ukuran}/toggle-status', [
    UkuranController::class,
    'toggleStatus'
])->name('ukurans.toggle-status');

Route::resource('ukurans', UkuranController::class)
    ->except(['show']);
        });

    Route::prefix('pemilik')
        ->name('pemilik.')
        ->middleware('role:pemilik')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('pemilik.dashboard');
            })->name('dashboard');
        });
});