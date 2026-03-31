<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BodyRepairOrderController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaintMaterialController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceItemController;
use App\Http\Controllers\ServiceOrderController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WalletPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.perform');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/wallet', [WalletPageController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletPageController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletPageController::class, 'withdraw'])->name('wallet.withdraw');

    Route::resource('clients', ClientController::class)->parameters(['clients' => 'id']);
    Route::resource('vehicles', VehicleController::class)->parameters(['vehicles' => 'id']);
    Route::resource('supplies', SupplyController::class)->parameters(['supplies' => 'id']);
    Route::resource('sales', SaleController::class)->parameters(['sales' => 'id']);
    Route::resource('service_orders', ServiceOrderController::class)->parameters(['service_orders' => 'id']);
    Route::resource('spare_parts', SparePartController::class)->parameters(['spare_parts' => 'id']);
    Route::resource('service_items', ServiceItemController::class)->parameters(['service_items' => 'id']);
    Route::resource('body_repair_orders', BodyRepairOrderController::class)->parameters(['body_repair_orders' => 'id']);
    Route::resource('paint_materials', PaintMaterialController::class)->parameters(['paint_materials' => 'id']);
    Route::resource('users', UserController::class)->parameters(['users' => 'id']);
});
