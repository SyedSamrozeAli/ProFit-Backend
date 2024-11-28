<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtherExpensePaymentsRequest;
use App\Http\Resources\OtherExpensePaymentsResource;
use App\Models\ExpenseCategory;
use App\Models\OtherExpensePayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherExpensePaymentsController extends Controller
{
    public function addExpense(OtherExpensePaymentsRequest $req)
    {

        try {
            DB::beginTransaction();

            $expenseCategoryId = ExpenseCategory::getCategoryId($req->expense_category);
            $status = "pending";

            if ($req->has('amount') && $req->payment_amount == $req->amount) {
                $status = "completed";
            }
            // Adding payment reciept image
            if ($req->has('payment_reciept') && $req->payment_reciept != null) {

                $paymentReciept = $req->file('payment_reciept');
                $imageName = time() . '.' . $paymentReciept->getClientOriginalExtension();
                $paymentReciept->move('images/other_expense/paymentsReciepts', $imageName);
            }


            $payment = OtherExpensePayments::updateOrCreate(
                [
                    'expense_id' => $req->expense_id,
                ],
                [
                    'expense_date' => $req->expense_date ?? null,
                    'due_date' => $req->due_date ?? null,
                    'expense_category' => $expenseCategoryId,
                    'amount' => $req->amount ?? 0,
                    'payment_method' => $req->payment_method,
                    'payment_amount' => $req->payment_amount,
                    'expense_status' => $status == "completed" ? 2 : 1,
                    'payment_reciept' => $imageName ?? null,
                ]
            );

            DB::commit();
            return successResponse("Record " . ($payment->wasRecentlyCreated ? "added" : "updated") . " successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function deletePayment($paymentId)
    {
        try {
            $deletedData = OtherExpensePayments::deletePayment($paymentId);

            if ($deletedData) {
                DB::commit();
                return successResponse("Record deleted successfully");
            } else {
                DB::rollback();
                return errorResponse("Record not found", 404);
            }

        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function getPayments(OtherExpensePaymentsRequest $request)
    {
        try {

            $paymentsData = OtherExpensePayments::getPaymentData($request->month, $request->year, $request->expenseId);
            if (!empty($paymentsData))
                return successResponse("Data retrieved successfully", OtherExpensePaymentsResource::collection($paymentsData));
            else
                return successResponse("No data found");
        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

}
