<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RopaController;
use App\Http\Controllers\RiskScoreController;
use App\Http\Controllers\ReviewController;


Route::get('/', function () {
    
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->name('admin.dashboard')
    ->middleware('auth', 'admin');

    
    // Regular User Dashboard
    Route::get('/user/dashboard', [DashboardController::class, 'user'])
        ->name('user.dashboard');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Admin dashboard route
    // Admin dashboard route
Route::get('/admin', [DashboardController::class, 'admin'])->name('admin');
Route::get('/account/edit', [DashboardController::class, 'edit'])->name('account.edit');
Route::patch('/account', [DashboardController::class, 'update'])->name('account.update');

 // ROPA resource routes
    Route::resource('ropa', RopaController::class);

});


Route::middleware(['auth', 'admin'])->group(function () {

    // Admin dashboard ROPA list
    Route::get('/admin/ropas', [RopaController::class, 'adminIndex'])
        ->name('admin.ropa.index');

    // View single ROPA details for review
    Route::get('/admin/ropas/{id}', [RopaController::class, 'show'])
        ->name('admin.ropa.show');

    // Approve a ROPA record
    Route::post('/admin/ropas/{id}/approve', [RopaController::class, 'approve'])
        ->name('admin.ropa.approve');

    // Reject a ROPA record
    Route::post('/admin/ropas/{id}/reject', [RopaController::class, 'reject'])
        ->name('admin.ropa.reject');


    // User management page for admin
    Route::get('/admin/users', [DashboardController::class, 'adminUsersIndex'])->name('admin.users.index');
    
    // Edit user form
    Route::get('/admin/users/{id}/edit', [DashboardController::class, 'editUser'])->name('admin.users.edit');

   // Update user details
    Route::put('/admin/users/{id}', [DashboardController::class, 'updateUser'])->name('admin.users.update');

   // Account Toggling 
    
    Route::patch('/admin/users/{user}/toggle-status', [DashboardController::class, 'toggleStatus'])
     ->name('admin.users.toggleStatus');
   // 
     Route::get('/admin/users/{user}', [DashboardController::class, 'show'])
    ->name('admin.users.show');


   // Review  route 
  Route::resource('reviews', ReviewController::class);

  // Risk Score 

  Route::resource('risk_scores', RiskScoreController::class);

});



require __DIR__.'/auth.php';
