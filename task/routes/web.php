<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\TaskCommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User management routes
    Route::resource('users', UserController::class);
    
    // Role management routes - protected by super admin middleware
    Route::middleware('super.admin')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('countries', CountryController::class);
    });

    // Task management routes
    Route::resource('tasks', TaskController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('task.comments.store');
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assignUsers'])->name('tasks.assign');
    Route::get('/tasks/{task}/comments/{comment}/download', [TaskController::class, 'downloadAttachment'])
        ->name('tasks.comments.download');

    // Deposit management routes
    Route::resource('deposits', DepositController::class);

    // Withdrawal management routes
    Route::resource('withdrawals', WithdrawalController::class);

    Route::patch('/comments/{comment}/complete', [TaskCommentController::class, 'markAsCompleted'])->name('task.comments.complete');
    Route::get('/attachments/{attachment}/download', [TaskCommentController::class, 'downloadAttachment'])->name('attachments.download');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
