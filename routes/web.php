<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RopaController;
use App\Http\Controllers\RiskScoreController;
use App\Http\Controllers\RiskWeightSettingController;
use App\Http\Controllers\Ropa\UserActivityController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\RopaIssueController;
use App\Http\Controllers\Admin\RiskBucketController;
use App\Http\Controllers\RiskController;
use App\Models\Comment;



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
Route::middleware(['auth'])->group(function () {
    Route::delete('/ropa/bulk-delete', [RopaController::class, 'bulkDelete'])->name('ropa.bulk-delete');
    Route::resource('ropa', RopaController::class);
});


// 2FA Verification Routes
Route::get('/2fa/verify', [TwoFactorController::class, 'show'])->name('2fa.verify');
Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify.post');
Route::post('/2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');


Route::get('/admin/ropas/test/export', function () {
    if (ob_get_length()) ob_clean();
    return \Spatie\SimpleExcel\SimpleExcelWriter::streamDownload('test.xlsx')
        ->addRow(['hello' => 'world', 'number' => 123])
        ->close();
});


Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/ropas/{id}/export', [RopaController::class, 'export'])
        ->name('admin.ropa.export');

    Route::get('/admin/ropas', [RopaController::class, 'adminIndex'])
        ->name('admin.ropa.index');

    Route::get('/admin/ropas/{ropa}', [RopaController::class, 'show'])
        ->name('admin.ropa.show');

    Route::post('/admin/ropas/{id}/approve', [RopaController::class, 'approve'])
        ->name('admin.ropa.approve');

    Route::post('/admin/ropas/{id}/reject', [RopaController::class, 'reject'])
        ->name('admin.ropa.reject');

    // User management
    Route::get('/admin/users', [DashboardController::class, 'adminUsersIndex'])->name('admin.users.index');
    Route::get('/admin/users/{id}/edit', [DashboardController::class, 'editUser'])->name('admin.users.edit');
    Route::get('/admin/users/create', [DashboardController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users/store', [DashboardController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [DashboardController::class, 'updateUser'])->name('admin.users.update');
    Route::patch('/admin/users/{user}/toggle-status', [DashboardController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
    Route::get('/admin/users/{user}', [DashboardController::class, 'show'])->name('admin.users.show');

    // Activity Routes
    Route::get('/activities/export', [UserActivityController::class, 'export'])->name('activities.export');
    Route::resource('activities', UserActivityController::class)->only(['index', 'show', 'destroy']);

    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');

    Route::post('/ropas/{ropa}/status', [RopaController::class, 'updateStatus'])->name('ropas.updateStatus');

    // ── Risk Buckets ──────────────────────────────────────────────
    Route::get('/admin/risk-buckets', [RiskBucketController::class, 'index'])
        ->name('admin.risk.buckets');

    Route::put('/admin/risk-buckets/{riskRegister}/level', [RiskBucketController::class, 'updateLevel'])
        ->name('admin.risk.buckets.updateLevel');
    // ─────────────────────────────────────────────────────────────
});


Route::get('/ropa/{id}/print', [RopaController::class, 'print'])->name('ropa.print');
Route::get('{ropa}/review', [RopaController::class, 'review'])->name('ropa.review');
Route::get('/{id}/review', [RopaController::class, 'show']);
Route::post('/ropa/{ropa}/move-risks', [RopaController::class, 'moveRisks']);

Route::post('ropa/{id}/send-email', [RopaController::class, 'sendEmail'])->name('ropa.sendEmail.post');


// Help Page
Route::get('/help', [App\Http\Controllers\HelpController::class, 'index'])->name('help');


Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{id}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::put('/reviews/{id}', [AdminReviewController::class, 'update'])->name('reviews.update');
    Route::put('/reviews/{id}/compliance', [AdminReviewController::class, 'updateCompliance'])->name('reviews.update.compliance');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/reviews/export/excel', [AdminReviewController::class, 'exportExcel'])->name('reviews.export.excel');
    Route::post('/reviews/bulk-action', [AdminReviewController::class, 'bulkAction'])->name('reviews.bulk.action');
    Route::post('/reviews/{review}/comment', [AdminReviewController::class, 'addComment'])->name('reviews.addComment');
    Route::delete('/reviews/comments/{comment}', [AdminReviewController::class, 'deleteComment'])->name('reviews.deleteComment');
});


Route::prefix('ticket')->name('ticket.')->middleware(['auth'])->group(function () {
    Route::get('/', [RopaIssueController::class, 'userIndex'])->name('index');
    Route::get('/create', [RopaIssueController::class, 'create'])->name('create');
    Route::post('/', [RopaIssueController::class, 'store'])->name('store');
    Route::get('/{id}', [RopaIssueController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [RopaIssueController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RopaIssueController::class, 'update'])->name('update');
    Route::delete('/{id}', [RopaIssueController::class, 'destroy'])->name('destroy');
});


Route::prefix('admin/tickets')->name('admin.tickets.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [RopaIssueController::class, 'index'])->name('index');
    Route::get('/{id}', [RopaIssueController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [RopaIssueController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RopaIssueController::class, 'update'])->name('update');
    Route::delete('/{id}', [RopaIssueController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/close', [RopaIssueController::class, 'close'])->name('close');
});


Route::get('/admin/review-risk-dashboard', [App\Http\Controllers\Admin\ReviewController::class, 'reviewRiskDashboard'])
    ->name('admin.review.risk.dashboard');


Route::middleware(['auth'])->group(function () {
    Route::get('risk-register/template/download', [RiskController::class, 'downloadTemplate'])
        ->name('risk-register.download-template');
    Route::post('risk-register/import', [RiskController::class, 'import'])
        ->name('risk-register.import');
    Route::post('risk-register/export-csv', [RiskController::class, 'exportCsv'])
        ->name('risk-register.export-csv');
    Route::post('risk-register/export-pdf', [RiskController::class, 'exportPdf'])
        ->name('risk-register.export-pdf');
    Route::delete('risk-register/bulk-delete', [RiskController::class, 'bulkDelete'])
        ->name('risk-register.bulk-delete');
    Route::resource('risk-register', RiskController::class);
});


Route::middleware('auth')->group(function () {
    Route::get('/activities', [UserActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/show/{activity}', [UserActivityController::class, 'show'])->name('activities.view');
    Route::get('/users/{userId}/activities', [UserActivityController::class, 'userActivities'])->name('users.activities');
    Route::get('/activities/model/{model}/{modelId}', [UserActivityController::class, 'modelActivities'])->name('activities.model');
    Route::get('/activities/json', [UserActivityController::class, 'jsonIndex'])->name('activities.json');
});


require __DIR__.'/auth.php';
