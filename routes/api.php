<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FinanceReportGenerationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TrainerAttendanceController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberAttendanceController;
use App\Http\Controllers\MemberPaymentsController;
use App\Http\Controllers\TrainerPaymentsController;
use App\Http\Controllers\InventoryPaymentsController;
use App\Http\Controllers\OtherExpensePaymentsController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------|
| API Routes                                                               |
|--------------------------------------------------------------------------|
*/


// Authentication Routes
Route::post('/admin/auth/login', [AdminAuthController::class, 'Login'])->name('login');
Route::post('/admin/auth/forgott-password', [AdminAuthController::class, 'ForgotPassword'])->name('forgotPassword');
Route::post('/admin/auth/reset-password', [AdminAuthController::class, 'ResetPassword'])->name('resetPassword');

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

    // Member Payments Routes
    Route::get('/member-payment', [MemberPaymentsController::class, 'getPayments']);
    Route::post('/member-payment', [MemberPaymentsController::class, 'addPayment']);
    Route::delete('/member-payment/{paymentId}', [MemberPaymentsController::class, 'deletePayment']);

    // Trainer Payments Routes
    Route::get('/trainer-payment', [TrainerPaymentsController::class, 'getPayments']);
    Route::post('/trainer-payment', [TrainerPaymentsController::class, 'addPayment']);
    Route::delete('/trainer-payment/{paymentId}', [TrainerPaymentsController::class, 'deletePayment']);

    // Inventory Payments Routes
    Route::get('/inventory-payment', [InventoryPaymentsController::class, 'getPayments']);
    Route::post('/inventory-payment', [InventoryPaymentsController::class, 'addPayment']);
    Route::delete('/inventory-payment/{paymentId}', [InventoryPaymentsController::class, 'deletePayment']);

    // OtherExpense Payments Routes
    Route::get('/other-expense-payment', [OtherExpensePaymentsController::class, 'getPayments']);
    Route::post('/other-expense-payment', [OtherExpensePaymentsController::class, 'addExpense']);
    Route::delete('/other-expense-payment/{paymentId}', [OtherExpensePaymentsController::class, 'deletePayment']);


    // Dashboard Routes
    Route::get('/active-members', [DashboardController::class, 'getTotalActiveMembers']);
    Route::get('/members-growth', [DashboardController::class, 'getMembersGrowth']);

    Route::get('/active-trainers', [DashboardController::class, 'getTotalActiveTrainers']);
    Route::get('/trainers-growth', [DashboardController::class, 'getTrainersGrowth']);

    Route::get('/total-revenue', [DashboardController::class, 'getTotalRevenue']);
    Route::get('/revenue-growth', [DashboardController::class, 'getRevenueGrowth']);

    Route::get('/attendance-rate', [DashboardController::class, 'getMemberAttendanceRate']);
    Route::get('/attendance-growth-rate', [DashboardController::class, 'getAttendanceGrowthRate']);

    Route::get('/monthly-expense', [DashboardController::class, 'getMonthlyExpenses']);
    Route::get('/expense-growth-rate', [DashboardController::class, 'getExpenseGrowthRate']);

    Route::get('/monthly-profit', [DashboardController::class, 'getMonthlyProfit']);
    Route::get('/monthly-profit-growth-rate', [DashboardController::class, 'getProfitGrowthRate']);

    Route::get('/monthly-revenue-expense', [DashboardController::class, 'getMonthlyRevenueExpenseData']);
    Route::get('/new-members-per-month', [DashboardController::class, 'getNewMembersPerMonth']);
    Route::get('/monthly-expense-distribution', [DashboardController::class, 'getExpenseDistribution']);
    Route::get('/membership-type-comparision', [DashboardController::class, 'getMembershipTypeComparison']);

});
Route::get('/finance-report-generation', [FinanceReportGenerationController::class, 'generateFinancialReport']);


