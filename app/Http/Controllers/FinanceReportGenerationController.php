<?php

namespace App\Http\Controllers;

use App\Models\FinanceReportGenerationModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MemberPayments;
use App\Models\TrainerPayments;
use App\Models\InventoryPayments;
use App\Models\OtherExpensePayments;
use Carbon\Carbon;

class FinanceReportGenerationController extends Controller
{
    public function generateFinancialReport(Request $request)
    {
        try {
            // Fetch the necessary data for the report
            $startDate = $request->start_date ?? Carbon::now()->startOfMonth();
            $endDate = $request->end_date ?? Carbon::now()->endOfMonth();

            // Calculate revenue from members
            $revenue = MemberPayments::calculateRevenue($startDate, $endDate);

            // Calculate expenses from trainer payments, inventory payments, and expenses
            $trainerPayments = TrainerPayments::calculateTrainerPayments($startDate, $endDate);
            $inventoryPayments = InventoryPayments::calculateInventoryPayments($startDate, $endDate);
            $otherExpenses = OtherExpensePayments::calculateOtherExpenses($startDate, $endDate);

            $expense = $trainerPayments + $inventoryPayments + $otherExpenses;

            // Calculate the net profit
            $netProfit = $revenue - $expense;

            $paymentHistory = FinanceReportGenerationModel::getPaymentHistoryDate($startDate, $endDate);

            // Prepare data to pass to the PDF view
            $reportData = [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'revenue' => $revenue,
                'trainer_payments' => $trainerPayments,
                'inventory_payments' => $inventoryPayments,
                'other_expenses' => $otherExpenses,
                'expenses' => $expense,
                'net_profit' => $netProfit,
                'payment_history' => $paymentHistory,
            ];
            // return view('finance_report', compact('reportData'));

            // Return the data to the PDF view for rendering
            $pdf = Pdf::loadView('finances_report', $reportData);

            // Download the PDF as a response
            return $pdf->download('financial_report_' . $startDate->format('Y_m_d') . '_to_' . $endDate->format('Y_m_d') . '.pdf');

            // return successResponse("Data fetched successfully", $reportData);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }

    }

}
