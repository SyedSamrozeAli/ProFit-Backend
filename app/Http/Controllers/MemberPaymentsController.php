<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberPaymentsRequest;
use App\Http\Resources\MemberPaymentsResource;
use App\Models\MemberPayments;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberPaymentsController extends Controller
{
    public function addPayment(MemberPaymentsRequest $req)
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
                $paymentReciept->move('images/member/paymentsReciepts', $imageName);
            }

            $membership = Membership::getMembershipID($req->membership_type);

            // Use updateOrCreate to handle insert/update logic
            $payment = MemberPayments::updateOrCreate(
                [
                    'member_id' => $req->member_id,
                    'payment_month' => date('m', strtotime($req->payment_date)),
                    'payment_year' => date('Y', strtotime($req->payment_date)),
                ],
                [
                    'payment_date' => $req->payment_date,
                    'membership_id' => $membership[0]->membership_id,
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


    public function deletePayment($paymentId)
    {
        try {
            $deletedData = MemberPayments::deletePayment($paymentId);

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

    public function getPayments(MemberPaymentsRequest $request)
    {
        try {

            $paymentsData = MemberPayments::getPaymentData($request->month, $request->year);

            return successResponse("Data retrieved successfully", MemberPaymentsResource::collection($paymentsData));

        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }
}
