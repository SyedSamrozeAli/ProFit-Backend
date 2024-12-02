<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryPaymentsRequest;
use App\Http\Resources\InventoryPaymentsResource;
use App\Models\InventoryPayments;
use Illuminate\Support\Facades\DB;

class InventoryPaymentsController extends Controller
{
    public function addPayment(InventoryPaymentsRequest $req)
    {
        try {
            DB::beginTransaction();

            $remaining_amount = $req->payment_amount - $req->amount_paid;
            $payment_status = 2; // 2 --> pending
            $dues = 0;
            $balance = 0;

            // Calculate dues and update payment status based on the remaining amount
            if ($remaining_amount >= 0) {
                $dues = $remaining_amount;

                if ($dues == 0) {
                    $payment_status = 1; // 1 --> completed
                }
            } else {
                $balance = -$remaining_amount;
                $payment_status = 1; // 1 --> completed
            }

            // Adding payment reciept image
            if ($req->has('payment_reciept') && $req->payment_reciept != null) {

                $paymentReciept = $req->file('payment_reciept');
                $imageName = time() . '.' . $paymentReciept->getClientOriginalExtension();
                $paymentReciept->move('images/inventory/paymentsReciepts', $imageName);
            }


            // Use updateOrCreate to handle insert/update logic
            $payment = InventoryPayments::updateOrCreate(
                [
                    'inventory_id' => $req->inventory_id,
                ],
                [
                    'payment_date' => $req->payment_date,
                    'payment_amount' => $req->payment_amount,
                    'amount_paid' => $req->amount_paid,
                    'due_amount' => $dues,
                    'balance' => $balance,
                    'payment_method' => $req->payment_method,
                    'payment_status' => $payment_status,
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
            $deletedData = InventoryPayments::deletePayment($paymentId);

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

    public function getPayments(InventoryPaymentsRequest $request)
    {
        try {

            $paymentsData = InventoryPayments::getPaymentData($request->month, $request->year, $request->inventoryId);

            return successResponse("Data retrieved successfully", InventoryPaymentsResource::collection($paymentsData));

        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
