<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TrainerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;

/*
|--------------------------------------------------------------------------|
| API Routes                                                               |
|--------------------------------------------------------------------------|
*/


// Authentication Routes
Route::post('/admin/auth/login', [AdminAuthController::class, 'Login']);

Route::middleware(['authenticate'])->group(function () {
    //Member Routes
    Route::get('/member', [MemberController::class, 'getMembers']);
    Route::get('/member/{memberId}', [MemberController::class, 'getSpecificMember'])->name('getSpecificMember');
    Route::post('/member', [MemberController::class, 'storeMember']);
    Route::put('/member/{memberId}', [MemberController::class, 'updateMember']);
    Route::delete('/member/{memberId}', [MemberController::class, 'deleteMember']);

    // Trainer Routes
    Route::get('/trainer', [TrainerController::class, 'getTrainers']);
    Route::post('/trainer', [TrainerController::class, 'storeTrainer']);
    Route::get('/trainer/{trainerId}', [TrainerController::class, 'getSpecificTrainer'])->name('getSpecificTrainer');
    Route::put('/trainer/{trainerId}', [TrainerController::class, 'updateTrainer']);
    Route::delete('/trainer/{trainerId}', [TrainerController::class, 'deleteTrainer']);

    //Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'getInventories']);
    Route::post('/inventory', [InventoryController::class, 'storeInventory']);
    Route::get('/inventory/{inventoryId}', [InventoryController::class, 'getSpecificInventory'])->name('getSpecificInventory');
    Route::put('/inventory/{inventoryId}', [InventoryController::class, 'updateInventory']);
    Route::delete('/inventory/{inventoryId}', [InventoryController::class, 'deleteInventory']);
});


