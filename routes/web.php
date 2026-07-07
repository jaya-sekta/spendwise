<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\UserRewardController;

// Controller Admin yang sudah ada sebelumnya
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\RouteLogController;

// ⬇️ TAMBAHAN: Import Controller Admin Baru untuk User dan Reward
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// 1. ROUTE UNTUK USER BIASA

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    Route::resource('expense', ExpenseController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('challenges', ChallengeController::class);

    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{reward}/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');

    Route::get('/user-rewards', [UserRewardController::class, 'index'])->name('user-rewards.index');

});


// ==========================================
// 2. ROUTE KHUSUS ADMIN
// ==========================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('categories', AdminCategoryController::class)
        ->except(['show'])
        ->names('admin.categories');

    // ⬇️ TAMBAHAN: Rute CRUD Kelola Pengguna (Mengarah ke App\Http\Controllers\Admin\UserController)
    Route::resource('users', AdminUserController::class)
        ->names('admin.users');

    // ⬇️ TAMBAHAN: Rute CRUD Kelola Reward & Stok (Mengarah ke App\Http\Controllers\Admin\RewardController)
    Route::resource('rewards', AdminRewardController::class)
        ->names('admin.rewards');

    // Dipindahkan ke sini, sekarang terlindungi middleware auth + role:admin
    Route::get('/logActivity', [RouteLogController::class, 'index'])->name('admin.logger');

});