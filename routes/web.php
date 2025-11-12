<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RopaController;
use App\Http\Controllers\RiskScoreController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RiskWeightSettingController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\TwoFactorController;


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
Route::patch('/profile/2fa-toggle', [ProfileController::class, 'toggleTwoFactor'])->name('2fa.toggle');


    
// Admin dashboard route
Route::get('/admin', [DashboardController::class, 'admin'])->name('admin');
Route::get('/account/edit', [DashboardController::class, 'edit'])->name('account.edit');
Route::patch('/account', [DashboardController::class, 'update'])->name('account.update');

 // ROPA resource routes
    Route::resource('ropa', RopaController::class);

});



// ✅ 2FA Verification Routes (Available to All Authenticated Users)
Route::get('/2fa/verify', [TwoFactorController::class, 'show'])->name('2fa.verify');
Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify.post');
Route::post('/2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');




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

    // Create Users 

    Route::get('/admin/users/create', [DashboardController::class, 'createUser'])->name('admin.users.create');

    // Store Users 

    Route::post('/admin/users/store', [DashboardController::class, 'store'])->name('admin.users.store');

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

  Route::resource('risk-weights', RiskWeightSettingController::class);


  // Activity Route

Route::get('/activities/export', [UserActivityController::class, 'export'])->name('activities.export');

    // Resource routes for viewing, showing, and deleting activities
 Route::resource('activities', UserActivityController::class)->only(['index', 'show', 'destroy']);


 Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');

 Route::post('/ropas/{ropa}/status', [App\Http\Controllers\RopaController::class, 'updateStatus'])->name('ropas.updateStatus');




});


 Route::get('/ropa/{id}/print', [RopaController::class, 'print'])->name('ropa.print'); Route::patch('/profile/2fa-toggle', [ProfileController::class, 'toggleTwoFactor'])->name('2fa.toggle');




require __DIR__.'/auth.php';
