<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainerPaymentsRequest;
use App\Http\Resources\TrainerPaymentsResource;
use App\Models\TrainerPayments;
use Illuminate\Support\Facades\DB;

class TrainerPaymentsController extends Controller
{
    public function addPayment(TrainerPaymentsRequest $req)
    {
        try {
            DB::beginTransaction();

            $remaining_amount = $req->payment_amount - $req->paid_amount;
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
            if ($req->has('payment_reciept')) {
                $paymentReciept = $req->file('payment_reciept');
                $imageName = time() . '.' . $paymentReciept->getClientOriginalExtension();
                $paymentReciept->move('images/trainer/paymentsReciepts', $imageName);
            }
            // Use updateOrCreate to handle insert/update logic
            $payment = TrainerPayments::updateOrCreate(
                [
                    'trainer_id' => $req->trainer_id,
                    'payment_month' => date('m', strtotime($req->payment_date)),
                    'payment_year' => date('Y', strtotime($req->payment_date)),
                ],
                [
                    'payment_date' => $req->payment_date,
                    'salary' => $req->salary,
                    'payment_amount' => $req->payment_amount,
                    'paid_amount' => $req->paid_amount,
                    'dues' => $dues,
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


    public function updatePayment(TrainerPaymentsRequest $req, $paymentId)
    {
        try {
            DB::beginTransaction();

            // Initialize the update query and bind parameters array
            $UpdateQuery = "UPDATE trainers_payments SET ";
            $UpdateFields = [];
            $UpdateValues = [];

            // Check which fields are present in the request and build the query accordingly
            // It will concatinate the values if the request contains multiple fields
            if ($req->has('payment_amount') && $req->has('paid_amount') && $req->has('payment_date') && $req->has('payment_method')) {
                // dd("here");
                $remaining_amount = $req->payment_amount - $req->paid_amount;
                $payment_status = 2;  // 2 --> pending
                $dues = 0;
                $balance = 0;

                // Calculate dues and update payment status based on the remaining amount
                if ($remaining_amount >= 0) {
                    $dues = $remaining_amount;

                    if ($dues == 0) {
                        $payment_status = 1;  // 1 --> completed
                    }
                } else {
                    $balance = -$remaining_amount;
                    $payment_status = 1;  // 1 --> completed

                }

                $UpdateFields[] = "payment_amount =?";
                $UpdateValues[] = $req->payment_amount;

                $UpdateFields[] = "paid_amount =?";
                $UpdateValues[] = $req->paid_amount;

                $UpdateFields[] = "payment_status =?";
                $UpdateValues[] = $payment_status;

                $UpdateFields[] = "dues =?";
                $UpdateValues[] = $dues;

                $UpdateFields[] = "balance =?";
                $UpdateValues[] = $balance;

                $UpdateFields[] = "payment_date =?";
                $UpdateValues[] = $req->payment_date;

                $UpdateFields[] = "payment_method =?";
                $UpdateValues[] = $req->payment_method;

            }

            if (empty($UpdateFields)) {
                return errorResponse("No fields to update", 400);
            }

            // Add the Payment ID to the bind parameters array
            $UpdateValues[] = $paymentId;

            $UpdateQuery .= implode(", ", $UpdateFields) . " WHERE trainer_payment_id = ?";

            TrainerPayments::updatePayment($UpdateQuery, $UpdateValues);

            DB::commit();

            return successResponse("Record updated successfully");


        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function deletePayment($paymentId)
    {
        try {
            $deletedData = TrainerPayments::deletePayment($paymentId);

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

    public function getPayments(TrainerPaymentsRequest $request)
    {
        try {

            $paymentsData = TrainerPayments::getPaymentData($request->month, $request->year, $request->trainerId);
            if (empty($paymentsData)) {
                return errorResponse("No data found");
            }
            return successResponse("Data retrieved successfully", TrainerPaymentsResource::collection($paymentsData));

        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
