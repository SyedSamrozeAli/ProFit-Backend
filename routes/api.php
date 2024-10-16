<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\TrainerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Authentication Routes
Route::post('/admin/auth/login', [AdminAuthController::class, 'Login']);

//Member Routes
Route::middleware(['authenticate'])->group(function () {
    Route::get('/member/', [MemberController::class, 'getMembers']);
    Route::get('/member/{memberId}', [MemberController::class, 'getSpecificMember']);
    Route::post('/member', [MemberController::class, 'storeMember']);
    Route::put('/member/{memberId}', [MemberController::class, 'updateMember']);
    Route::delete('/member/{memberId}', [MemberController::class, 'deleteMember']);
});

// Trainer Routes
Route::middleware(['authenticate'])->group(function () {
    Route::get('/trainer', [TrainerController::class, 'getTrainers'])->name('getFilteredTrainers');
    Route::get('/trainer/{trainerId}', [TrainerController::class, 'getSpecificTrainer']);
    Route::post('/trainer', [TrainerController::class, 'storeTrainer']);
    Route::put('/trainer/{trainerId}', [TrainerController::class, 'updateTrainer']);
    Route::delete('/trainer/{trainerId}', [TrainerController::class, 'deleteTrainer']);
});
