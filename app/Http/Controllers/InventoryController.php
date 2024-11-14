<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\Http\Resources\InventoryResource;
use App\Models\Equipment;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class InventoryController extends Controller
{

    public function storeInventory(InventoryRequest $request)
    {
        try {
            $newInventoryId = Inventory::addInventory($request);

            // Finding the newly added Inventory
            $newInventory = Inventory::findInventory('inventory_id', $newInventoryId);

            // Return a success response with the Inventory data
            return successResponse("Inventory added successfully", new InventoryResource($newInventory[0]));

        } catch (\Exception $e) {

            return errorResponse($e->getMessage());
        }


    }


    // Fetching a specific Inventory through ID
    public function getSpecificInventory($InventoryId)
    {
        try {

            // Finding the Inventory by ID
            $Inventory = Inventory::findInventory('inventory_id', $InventoryId);

            if (!empty($Inventory)) {

                // Return a success response with the Inventory data
                return successResponse("Inventory retrieved successfully", InventoryResource::make($Inventory[0]));

            } else {

                // Return an error response if the Inventory is not found
                return errorResponse("Inventory not found", 404);
            }

        } catch (\Exception $e) {

            return errorResponse($e->getMessage());
        }

    }


    public function updateInventory(InventoryRequest $request, $InventoryId)
    {
        try {

            $equipmentId = Inventory::getValue('equipment_id', $InventoryId);

            // Initialize the update query and bind parameters array
            $invUpdateQuery = "UPDATE inventory SET ";
            $invUpdateFields = [];
            $invUpdateValues = [];

            $equipUpdateQuery = "UPDATE equipments SET ";
            $equipUpdateFields = [];
            $equipUpdateValues = [];


            // Check which fields are present in the request and build the query accordingly
            // It will concatinate the values if the request contains multiple fields
            if ($request->has('item_name')) {
                $equipUpdateFields[] = "equipment_name = ?";
                $equipUpdateValues[] = $request->item_name;
            }

            if ($request->has('description')) {
                $equipUpdateFields[] = "description = ?";
                $equipUpdateValues[] = $request->description;
            }

            if ($request->has('category')) {
                $equipUpdateFields[] = "category = ?";
                $equipUpdateValues[] = $request->category;
            }

            if ($request->has('quantity')) {
                $invUpdateFields[] = "quantity = ?";
                $invUpdateValues[] = $request->quantity;

                $prevQty = Inventory::getValue('quantity', $InventoryId);
                $equipQty = Equipment::getValue('quantity', $equipmentId);

                // deducting the previous quantity and then adding the updated quantity
                $newEquipQty = $equipQty - $prevQty + $request->quantity;

                $equipUpdateFields[] = "quantity = ?";
                $equipUpdateValues[] = $newEquipQty;

                $cost_per_unit = Inventory::getValue('cost_per_unit', $InventoryId);
                $total_price = $cost_per_unit * $request->quantity;

                $invUpdateFields[] = "total_price = ?";
                $invUpdateValues[] = $total_price;
            }

            if ($request->has('warranty_period')) {
                $invUpdateFields[] = "warranty_period = ?";
                $invUpdateValues[] = $request->warranty_period;
            }

            if ($request->has('cost_per_unit')) {
                $invUpdateFields[] = "cost_per_unit = ?";
                $invUpdateValues[] = $request->cost_per_unit;

                $equipUpdateFields[] = "price = ?";
                $equipUpdateValues[] = $request->cost_per_unit;

                // Update the total price of the equipment
                $qty = Inventory::getValue('quantity', $InventoryId);
                $newTotalPrice = $qty * $request->cost_per_unit;

                $invUpdateFields[] = "total_price = ?";
                $invUpdateValues[] = $newTotalPrice;

            }

            if ($request->has('supplier_name')) {
                $invUpdateFields[] = "supplier_name = ?";
                $invUpdateValues[] = $request->supplier_name;
            }

            if ($request->has('purchase_date')) {
                $invUpdateFields[] = "purchase_date = ?";
                $invUpdateValues[] = $request->purchase_date;
            }


            // If no fields were sent, return an error response
            if (empty($invUpdateFields) && empty($equipUpdateFields)) {
                return errorResponse("No fields to update", 400);
            }

            // Add the Inventory ID to the bind parameters array
            $invUpdateValues[] = $InventoryId;
            $equipUpdateValues[] = $equipmentId;

            // Finalize the query
            // 'implode' transforms the elements of an array in to a single string seperated by a delimiter in this case it is a comma
            $invUpdateQuery .= implode(", ", $invUpdateFields) . " WHERE inventory_id = ?";
            $equipUpdateQuery .= implode(", ", $equipUpdateFields) . " WHERE equipment_id = ?";

            // Execute the update query
            Inventory::updateInventory($invUpdateQuery, $invUpdateValues);
            Equipment::updateEquipment($equipUpdateQuery, $equipUpdateValues);

            // Find the updated Inventory
            $updatedInventory = Inventory::findInventory('inventory_id', $InventoryId);

            if (!empty($updatedInventory)) {

                // Return a success response with the updated Inventory data
                return successResponse("Inventory updated successfully", InventoryResource::make($updatedInventory[0]));

            } else {
                return errorResponse("Inventory not found", 404);
            }

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }


    public function deleteInventory($InventoryId)
    {
        try {

            // Find the Equipment ID associated with the Inventory
            $equipmentId = Inventory::getValue('equipment_id', $InventoryId);

            //Deduct the quantity of the equipment
            $qty = Inventory::getValue('quantity', $InventoryId);
            $newEquipQuantity = Equipment::getValue('quantity', $equipmentId) - $qty;

            // If the new quantity of the equipment becomes 0, update the status to 'Out of Stock' and set quantity to 0
            if ($newEquipQuantity == 0) {
                // Update the status of the Equipment
                Equipment::updateEquipment("UPDATE equipments SET equipment_status = 'Out of Stock',quantity=0 WHERE equipment_id =?", [$equipmentId]);
            } else {
                // Update the quantity of the equipment
                Equipment::updateEquipment("UPDATE equipments SET quantity =? WHERE equipment_id =?", [$newEquipQuantity, $equipmentId]);
            }

            // Delete the Inventory from the database
            $deletedRows = DB::delete("DELETE FROM inventory WHERE inventory_id = ?", [$InventoryId]);

            // Check if any row was actually deleted
            if ($deletedRows) {
                return successResponse("Inventory deleted successfully");
            } else {
                return errorResponse("Inventory not found", 404);
            }

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }


    public function getInventories(InventoryRequest $request)
    {
        try {
            $inventories = Inventory::getInventories();

            // Check if Inventories are found
            if (!empty($inventories)) {
                return successResponse("Inventories retrieved successfully", InventoryResource::collection($inventories), $request->paginate);
            }

            return errorResponse("No Inventories found", 404);

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }


}
