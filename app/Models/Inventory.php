<?php

namespace App\Models;

use App\Http\Requests\InventoryRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';

    protected $fillable = [
        'quantity',
        'cost_per_unit',
        'total_price',
        'warranty_period',
        'usage_duration',
        'equipment_id',
        'supplier_name',
        'mantainance_date',
        'purchase_date'
    ];
    protected $casts = [

        'mantainance_date' => 'datetime:Y-m-d H:m:s',
        'purchase_date' => 'datetime:Y-m-d H:m:s',
    ];

    static public function addInventory(InventoryRequest $req)
    {

        $itemName = $req->item_name;

        $equipment = DB::select("SELECT equipment_id FROM equipments WHERE equipment_name = ? OR equipment_name LIKE ?", [$itemName, "%$itemName%"]);


        // If equipment not found, add it to the equipments table and get its ID
        if (empty($equipment)) {
            $equipmentId = Equipment::addEquipment($itemName, $req->category, $req->cost_per_unit, $req->quantity, $req->description);
        } else {
            //update equip quantity,price
            $equipmentId = $equipment[0]->equipment_id;

            Equipment::updateQuantityAndPrice($equipment[0]->equipment_id, $req->quantity, $req->cost_per_unit);
        }

        // Inserting data into inventory
        DB::insert("INSERT INTO inventory (equipment_id,cost_per_unit,quantity,total_price,mantainance_date,warranty_period,usage_duration,purchase_date,supplier_name)
                    VALUES (?,?,?,?,?,?,?,?,?)",
            [
                $equipmentId,
                $req->cost_per_unit,
                $req->quantity,
                $req->quantity * $req->cost_per_unit,
                NULL,
                $req->warranty_period,
                NULL,
                $req->purchase_date,
                $req->supplier_name
            ]
        );

        // Returning the id of the last inserted item
        return DB::getPdo()->lastInsertId();

    }

    static public function findInventory($filter, $value)
    {
        return DB::select("SELECT * FROM inventory WHERE " . $filter . "=?", [$value]);
    }

    static public function getValue($value, $id)
    {
        return DB::select("SELECT $value FROM inventory WHERE inventory_id=?", [$id])[0]->$value;
    }

    static public function updateInventory($updateQuery, $updateValues)
    {
        DB::update($updateQuery, $updateValues);

    }

    static public function getInventories()
    {
        return DB::select("SELECT * FROM inventory");
    }
}
