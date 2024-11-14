<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentController extends Controller
{
    public function getEquipments()
    {
        try {
            DB::beginTransaction();
            $equipments = Equipment::getAllEquipments();

            if (!empty($equipments)) {
                DB::commit();
                return successResponse("Equipments fetched successfully", EquipmentResource::collection($equipments));
            } else {
                return errorResponse("No equipments available");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return errorResponse($e->getMessage());
        }
    }

    public function deleteEquipments($equipId)
    {
        try {

            DB::beginTransaction();
            $deletedRows = Equipment::deleteEquipment($equipId);


            if ($deletedRows > 0) {
                DB::commit();
                return successResponse("Equipment deleted successfully");
            } else {
                DB::rollback();
                return errorResponse("Equipment not found", 404);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return errorResponse($e->getMessage());
        }
    }
}
