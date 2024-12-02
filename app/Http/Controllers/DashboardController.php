<?php

namespace App\Http\Controllers;

use App\Models\InventoryPayments;
use App\Models\Member;
use App\Models\Membership;
use App\Models\OtherExpensePayments;
use App\Models\Trainer;
use App\Models\MemberPayments;
use App\Models\MemberAttendance;
use App\Models\TrainerPayments;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getTotalActiveMembers()
    {
        try {

            $totalActiveMembers = Member::getActiveMembers();


            if (!empty($totalActiveMembers)) {
                return successResponse("Data retrieved successfully", ["total_active_members" => $totalActiveMembers]);
            } else {
                return errorResponse("Data not found");
            }
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getMembersGrowth()
    {
        try {
            $counts = Member::getMembersGrowth();

            $currentMonth = $counts['current_month'];
            $lastMonth = $counts['last_month'];

            // Calculate growth percentage
            if ($lastMonth > 0) {
                $growthPercentage = (($currentMonth - $lastMonth) / $lastMonth) * 100;
            } else {
                $growthPercentage = $currentMonth > 0 ? 100 : 0;
            }

            return successResponse("Growth percentage calculated successfully", [
                "current_month_members" => $currentMonth,
                "last_month_members" => $lastMonth,
                "growth_percentage" => round($growthPercentage, 2)
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getTotalActiveTrainers()
    {
        try {

            $totalActiveTrainers = Trainer::getActiveTrainers();


            if (!empty($totalActiveTrainers)) {
                return successResponse("Data retrieved successfully", ["total_active_trainers" => $totalActiveTrainers]);
            } else {
                return errorResponse("Data not found");
            }
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getTrainersGrowth()
    {
        try {
            $counts = Trainer::getTrainersGrowth();

            $currentMonth = $counts['current_month'];
            $lastMonth = $counts['last_month'];

            // Calculate growth percentage
            if ($lastMonth > 0) {
                $growthPercentage = (($currentMonth - $lastMonth) / $lastMonth) * 100;
            } else {
                $growthPercentage = $currentMonth > 0 ? 100 : 0;
            }

            return successResponse("Growth percentage calculated successfully", [
                "current_month_trainers" => $currentMonth,
                "last_month_trainers" => $lastMonth,
                "growth_percentage" => round($growthPercentage, 2)
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getTotalRevenue(Request $req, $m = null, $y = null)
    {
        try {
            if ($req->has('month')) {
                $month = $req->month;
            } else {
                $month = $m;
            }

            if ($req->has('year')) {
                $year = $req->year;
            } else {
                $year = $y;
            }

            $totalRevenue = MemberPayments::getTotalRevenue($month, $year);

            return successResponse("Total revenue calculated successfully", [
                "total_revenue" => $totalRevenue
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getRevenueGrowth()
    {
        try {
            $counts = MemberPayments::getRevenueGrowth();

            $currentMonth = $counts['current_month'];
            $lastMonth = $counts['last_month'];

            // Calculate growth percentage
            if ($lastMonth > 0) {
                $growthPercentage = (($currentMonth - $lastMonth) / $lastMonth) * 100;
            } else {
                $growthPercentage = $currentMonth > 0 ? 100 : 0;
            }

            return successResponse("Growth percentage calculated successfully", [
                "current_month_revenue" => $currentMonth,
                "last_month_revenue" => $lastMonth,
                "growth_percentage" => round($growthPercentage, 2)
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getMemberAttendanceRate()
    {
        try {

            $attendanceRate = MemberAttendance::getMemberAttendanceRate();

            return successResponse("Attendance rate calculated successfully", [
                "attendance_rate" => $attendanceRate
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getAttendanceGrowthRate()
    {
        try {
            $attendanceGrowth = MemberAttendance::getMemberAttendanceGrowthRate();

            $curr = $attendanceGrowth["currentMonth"][0];
            $prev = $attendanceGrowth["previousMonth"][0];

            // Default to 0 if total_days is 0 or null
            $currRate = $curr->total_days > 0 ? ($curr->present_days / $curr->total_days) : 0;
            $prevRate = $prev->total_days > 0 ? ($prev->present_days / $prev->total_days) : 0;

            // Calculate growth percentage
            $growthPercentage = ($currRate - $prevRate) * 100;

            return successResponse("Attendance Growth percentage calculated successfully", [
                "growth_percentage" => round($growthPercentage, 2),
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getMonthlyExpenses(Request $req, $m = null, $y = null)
    {
        try {
            if ($req->has('month')) {
                $month = $req->month;
            } else {
                $month = $m;
            }

            if ($req->has('year')) {
                $year = $req->year;
            } else {
                $year = $y;
            }

            $trainerSalaries = TrainerPayments::getTrainerSalariesMonth($month, $year);
            $otherExpenses = OtherExpensePayments::getOtherExpensesMonth($month, $year);
            $inventoryPayments = InventoryPayments::getInventoryPaymentsMonth($month, $year);

            $totalExpenses = $trainerSalaries + $otherExpenses + $inventoryPayments;

            return successResponse("Expenses calculated successfully", [
                "total_expenses" => round($totalExpenses, 2),
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getExpenseGrowthRate()
    {
        try {
            $currentMonthExpenses = OtherExpensePayments::getMonthlyExpenses(0);
            $previousMonthExpenses = OtherExpensePayments::getMonthlyExpenses(1);

            if ($previousMonthExpenses == 0) {
                $growthRate = $currentMonthExpenses > 0 ? 100 : 0;
            } else {
                $growthRate = (($currentMonthExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100;
            }

            return successResponse("Expense growth rate calculated successfully", [
                "growth_rate" => -round($growthRate, 2),
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getMonthlyProfit(Request $req, $m = null, $y = null)
    {
        try {
            if ($req->has('month')) {
                $month = $req->month;
            } else {
                $month = $m;
            }

            if ($req->has('year')) {
                $year = $req->year;
            } else {
                $year = $y;
            }

            //calculating expense
            $trainerSalaries = TrainerPayments::getTrainerSalariesMonth($month, $year);
            $otherExpenses = OtherExpensePayments::getOtherExpensesMonth($month, $year);
            $inventoryPayments = InventoryPayments::getInventoryPaymentsMonth($month, $year);

            $totalExpenses = $trainerSalaries + $otherExpenses + $inventoryPayments;

            //calculating revenue
            $totalRevenue = MemberPayments::getTotalRevenue($month, $year);

            //calculating profit
            $profit = $totalRevenue - $totalExpenses;

            return successResponse("Monthly profit calculated successfully", [
                "total_profit" => round($profit, 2),
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getProfitGrowthRate(Request $req)
    {
        try {
            // Get current month and year
            $currentDate = new \DateTime();
            $currMonth = $currentDate->format('m');
            $currYear = $currentDate->format('Y');

            // Get previous month and adjust year if necessary
            $previousDate = clone $currentDate;
            $previousDate->modify('-1 month');
            $prevMonth = $previousDate->format('m');
            $prevYear = $previousDate->format('Y');

            // Fetch current month's profit
            $currMonthResponse = $this->getMonthlyProfit($req, $currMonth, $currYear);
            $currMonthProfit = $currMonthResponse->getData()->data->total_profit ?? 0;

            // Fetch previous month's profit
            $prevMonthResponse = $this->getMonthlyProfit($req, $prevMonth, $prevYear);
            $prevMonthProfit = $prevMonthResponse->getData()->data->total_profit ?? 0;

            // Calculate growth rate
            if ($prevMonthProfit == 0) {
                $growthRate = $currMonthProfit > 0 ? 100 : 0;
            } else {
                $growthRate = (($currMonthProfit - $prevMonthProfit) / $prevMonthProfit) * 100;
            }

            // Return success response with growth rate
            return successResponse("Profit growth rate calculated successfully", [
                "growth_rate_percentage" => round($growthRate, 2)
            ]);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return errorResponse($e->getMessage());
        }
    }

    public function getMonthlyRevenueExpenseData(Request $req)
    {
        try {
            $monthlyData = [];

            for ($month = 1; $month <= 12; $month++) {
                // Fetch revenue and expense for each month
                $revenue = MemberPayments::getTotalRevenue($month, $req->year);
                $expense = $this->getMonthlyExpenses($req, $month, $req->year)->getData()->data->total_expenses ?? 0;

                // Prepare data format for chart
                $monthlyData[] = [
                    'month' => date('M', mktime(0, 0, 0, $month, 1)),
                    'revenue' => $revenue,
                    'expense' => $expense,
                ];

            }
            return successResponse("Data retrieved successfully", $monthlyData);

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getNewMembersPerMonth(Request $request)
    {
        try {
            // Retrieve the year from request parameters
            $year = $request->input('year', date('Y')); // Default to current year if not provided

            // Fetch new members data from the model
            $newMembersData = Member::getNewMembersPerMonth($year);

            // Initialize the monthly data structure
            $monthlyData = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthlyData[$month] = 0; // Initialize with 0 new members
            }

            // Populate the data for months with new members
            foreach ($newMembersData as $data) {
                $monthlyData[$data->month] = $data->new_members;
            }

            // Format the data for the line graph
            $formattedData = [];
            foreach ($monthlyData as $month => $newMembers) {
                $formattedData[] = [
                    'month' => date('M', mktime(0, 0, 0, $month, 1)), // Format month as Jan, Feb, etc.
                    'new_members' => $newMembers
                ];
            }

            return successResponse("Data fetched successfully", $formattedData);

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getExpenseDistribution(Request $request)
    {
        try {

            $year = $request->input('year', date('Y')); // Default to current year if not provided
            $month = $request->input('month', date('m')); // Default to current year if not provided


            // Fetch expenses data
            $trainerSalaries = TrainerPayments::getTrainerSalariesMonth($month, $year);
            $inventoryPayments = InventoryPayments::getInventoryPaymentsMonth($month, $year);
            $otherExpenses = OtherExpensePayments::getOtherExpensesMonth($month, $year);

            // Calculate the total expenses
            $totalExpenses = $trainerSalaries + $inventoryPayments + $otherExpenses;

            // Avoid division by zero
            if ($totalExpenses == 0) {
                return successResponse("No expense record for this month", [
                    'data' => [
                        'Trainer Salaries' => 0,
                        'Inventory Payments' => 0,
                        'Other Expenses' => 0
                    ]
                ]);

            }

            // Calculate percentages
            $trainerSalariesPercentage = ($trainerSalaries / $totalExpenses) * 100;
            $inventoryPaymentsPercentage = ($inventoryPayments / $totalExpenses) * 100;
            $otherExpensesPercentage = ($otherExpenses / $totalExpenses) * 100;

            // Prepare data for the doughnut chart in percentage form
            $expenseDistribution = [
                'Trainer Salaries' => round($trainerSalariesPercentage, 2),
                'Inventory Payments' => round($inventoryPaymentsPercentage, 2),
                'Other Expenses' => round($otherExpensesPercentage, 2)
            ];

            return successResponse("Data fetched successfully", $expenseDistribution);

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getMembershipTypeComparison()
    {
        try {

            $membershipData = Membership::getMembershipTypeData();

            $formattedData = [];
            foreach ($membershipData as $data) {
                $formattedData[] = [
                    'membership_type' => ucfirst($data->membership_type), // Capitalize type
                    'total_members' => $data->total_members
                ];
            }

            return successResponse("Membership type comparison data retrieved successfully", [
                "membership_comparison" => $formattedData
            ]);
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }


}
