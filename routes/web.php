<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RopaController;
use App\Http\Controllers\RiskScoreController;
use App\Http\Controllers\RiskWeightSettingController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\RopaIssueController;


Route::get('/', function () {
    
    return view('auth.login');
});


Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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


});



// ROPA resource routes
Route::resource('ropa', RopaController::class);




// ✅ 2FA Verification Routes (Available to All Authenticated Users)
Route::get('/2fa/verify', [TwoFactorController::class, 'show'])->name('2fa.verify');
Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify.post');
Route::post('/2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');


Route::get('/admin/ropas/test/export', function () {
    // Make sure nothing was sent before output
    if (ob_get_length()) {
        ob_clean();
    }

    return \Spatie\SimpleExcel\SimpleExcelWriter::streamDownload('test.xlsx')
        ->addRow([
            'hello' => 'world',
            'number' => 123,
        ])
        ->close();
});




Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/ropas/{id}/export', [RopaController::class, 'export'])
    ->name('admin.ropa.export');

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


  

  // Risk Score 

 


  // Activity Route

Route::get('/activities/export', [UserActivityController::class, 'export'])->name('activities.export');

    // Resource routes for viewing, showing, and deleting activities
 Route::resource('activities', UserActivityController::class)->only(['index', 'show', 'destroy']);


 Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');

 Route::post('/ropas/{ropa}/status', [App\Http\Controllers\RopaController::class, 'updateStatus'])->name('ropas.updateStatus');




});


Route::get('/ropa/{id}/print', [RopaController::class, 'print'])->name('ropa.print');

// Handle sending the email (POST)
Route::post('ropa/{id}/send-email', [RopaController::class, 'sendEmail'])->name('ropa.sendEmail.post');
Route::patch('/profile/2fa-toggle', [ProfileController::class, 'toggleTwoFactor'])->name('2fa.toggle');

// Review routes
// Review routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');

    Route::get('/reviews/{id}', [AdminReviewController::class, 'show'])->name('reviews.show');

    Route::put('/reviews/{id}', [AdminReviewController::class, 'update'])->name('reviews.update');

    Route::put('/reviews/{id}/compliance', [AdminReviewController::class, 'updateCompliance'])
        ->name('reviews.update.compliance');

    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // ⭐ NEW: Export Reviews to Excel (matching your view)
    Route::get('/reviews/export/excel', [AdminReviewController::class, 'exportExcel'])
        ->name('reviews.export.excel');

    // ⭐ REQUIRED: Bulk Delete + Bulk Export route
    Route::post('/reviews/bulk-action', [AdminReviewController::class, 'bulkAction'])
        ->name('reviews.bulk.action');   // <--- THIS FIXES THE ERROR
});



Route::prefix('ticket')->name('ticket.')->group(function () {

    // List all tickets
    Route::get('/', [RopaIssueController::class, 'index'])->name('index');

    // Create form
    Route::get('/create', [RopaIssueController::class, 'create'])->name('create');

    // Store ticket
    Route::post('/', [RopaIssueController::class, 'store'])->name('store');

    // Show ticket
    Route::get('/{id}', [RopaIssueController::class, 'show'])->name('show');

    // Edit ticket
    Route::get('/{id}/edit', [RopaIssueController::class, 'edit'])->name('edit');

    // Update ticket
    Route::put('/{id}', [RopaIssueController::class, 'update'])->name('update');

    // Delete ticket
    Route::delete('/{id}', [RopaIssueController::class, 'destroy'])->name('destroy');

});



Route::prefix('admin/tickets')->name('admin.tickets.')->middleware(['auth', 'admin'])->group(function () {

    // List all tickets (pending + resolved)
    Route::get('/', [RopaIssueController::class, 'index'])->name('index');

    // Show ticket
    Route::get('/{id}', [RopaIssueController::class, 'show'])->name('show');

    // Edit ticket
    Route::get('/{id}/edit', [RopaIssueController::class, 'edit'])->name('edit');

    // Update ticket
    Route::put('/{id}', [RopaIssueController::class, 'update'])->name('update');

    // Delete ticket
    Route::delete('/{id}', [RopaIssueController::class, 'destroy'])->name('destroy');
});



Route::get('/admin/review-risk-dashboard', [App\Http\Controllers\Admin\ReviewController::class, 'reviewRiskDashboard'])
    ->name('admin.review.risk.dashboard');





require __DIR__.'/auth.php';
