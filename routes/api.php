<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TrainerAttendanceController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberAttendanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------|
| API Routes                                                               |
|--------------------------------------------------------------------------|
*/


// Authentication Routes
Route::post('/admin/auth/login', [AdminAuthController::class, 'Login']);

Route::middleware(['authenticate'])->group(function () {

    //Logout Route
    Route::get('/admin/auth/logout', [AdminAuthController::class, 'Logout']);

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

    //Equipment Routes
    Route::get('/equipment', [EquipmentController::class, 'getEquipments']);
    Route::delete('/equipment/{equipmentId}', [EquipmentController::class, 'deleteEquipments']);

    //Membership Routes
    Route::get('/membership', [MembershipController::class, 'getAllMemberships']);
    Route::post('/membership', [MembershipController::class, 'addMembership']);
    Route::get('/membership/{membershipId}', [MembershipController::class, 'getSpecificMembership']);
    Route::put('/membership/{membershipId}', [MembershipController::class, 'updateMembership']);
    Route::delete('/membership/{membershipId}', [MembershipController::class, 'deleteMembership']);

    //Member Attendance Routes
    Route::get('/member-attendance', [MemberAttendanceController::class, 'getAttendance']);
    Route::post('/member-attendance', [MemberAttendanceController::class, 'addAttendance']);

    // Trainer Attendance Routes
    Route::get('/trainer-attendance', [TrainerAttendanceController::class, 'getAttendance']);
    Route::post('/trainer-attendance', [TrainerAttendanceController::class, 'addAttendance']);
});


