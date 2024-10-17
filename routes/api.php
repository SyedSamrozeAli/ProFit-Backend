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
Route::middleware(['authenticate'])->prefix('member')->group(function () {
    Route::get('/', [MemberController::class, 'getMembers']);
    Route::get('/{memberId}', [MemberController::class, 'getSpecificMember']);
    Route::post('/', [MemberController::class, 'storeMember']);
    Route::put('/{memberId}', [MemberController::class, 'updateMember']);
    Route::delete('/{memberId}', [MemberController::class, 'deleteMember']);
});

// Trainer Routes
Route::middleware(['authenticate'])->group(function () {
    Route::get('/trainer', [TrainerController::class, 'getTrainers']);
    Route::post('/trainer', [TrainerController::class, 'storeTrainer']);
    Route::get('/trainer/{trainerId}', [TrainerController::class, 'getSpecificTrainer']);
    Route::put('/trainer/{trainerId}', [TrainerController::class, 'updateTrainer']);
    Route::delete('/trainer/{trainerId}', [TrainerController::class, 'deleteTrainer']);
});
