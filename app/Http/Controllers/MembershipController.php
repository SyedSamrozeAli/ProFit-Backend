<?php

namespace App\Http\Controllers;

use App\Http\Requests\MembershipRequest;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class MembershipController extends Controller
{
    // Create a new membership
    public function addMembership(MembershipRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->only(['membership_type']);
            Membership::createMembership($data);

            DB::commit();
            return successResponse("Membership Created successfully");
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 500);
        }
    }

    // Get a single membership
    public function getSpecificMembership($membershipId)
    {
        try {
            $membership = Membership::getMembershipById($membershipId);

            if (empty($membership)) {
                return errorResponse("Membership not found");
            }

            return successResponse("Membership fetched successfully", $membership);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    // Get all memberships
    public function getAllMemberships()
    {
        try {
            $memberships = Membership::getAllMemberships();

            return successResponse("Membership fetched successfully", $memberships);
        } catch (Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    // Update a membership
    public function updateMembership(MembershipRequest $request, $membershipId)
    {
        DB::beginTransaction();

        try {
            $data = $request->only(['membership_type']);
            $updated = Membership::membershipUpdate($membershipId, $data);

            if ($updated) {
                DB::commit();
                return successResponse("Membership updated successfully", $updated);
            } else {
                DB::rollBack();
                return errorResponse("Membership not found");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 500);
        }
    }

    // Delete a membership
    public function deleteMembership($membershipId)
    {
        DB::beginTransaction();

        try {
            $deleted = Membership::deleteMembership($membershipId);

            if ($deleted) {
                DB::commit();
                return successResponse("Membership deleted successfully");
            } else {
                DB::rollBack();
                return errorResponse("Membership not found", 400);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 500);
        }
    }
}
