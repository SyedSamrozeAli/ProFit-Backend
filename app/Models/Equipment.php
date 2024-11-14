<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';
    protected $primaryKey = 'equipment_id';

    protected $fillable = [
        'equipment_name',
        'category',
        'price',
        'quantity',
        'equipment_status',
        'description'
    ];


    static public function addEquipment($name, $category, $price, $quantity, $description)
    {
        DB::insert("INSERT INTO equipments (equipment_name,category,price,quantity,equipment_status,description)
                    VALUES (?,?,?,?,?,?)",
            [
                $name,
                $category,
                $price,
                $quantity,
                'In Stock',
                $description
            ]
        );

        // Extracting the ID of newly added equipment
        $equipId = DB::select("SELECT equipment_id FROM equipments WHERE equipment_name=? AND category=?", [$name, $category])[0]->equipment_id;

        // Returning the id of the newly added equipment
        return $equipId;
    }


    static public function updateQuantityAndPrice($equipId, $newQty, $price)
    {
        DB::transaction(
            function () use ($equipId, $newQty, $price) {

                $equipment = DB::select("SELECT quantity FROM equipments WHERE equipment_id=?", [$equipId]);

                $newQty = $equipment[0]->quantity + $newQty;

                DB::update("UPDATE equipments SET quantity=?, price=? , equipment_status=? WHERE equipment_id=?", [$newQty, $price, 'In Stock', $equipId]);
            }
        );
    }

    static public function getEquipment($equipId)
    {
        
        return ( DB::select("SELECT * FROM equipments WHERE equipment_id=?", [$equipId])[0]);
    }

    static public function getValue($value, $id)
    {
        return DB::select("SELECT $value FROM equipments WHERE equipment_id=?", [$id])[0]->$value;
    }

    static public function updateEquipment($updateQuery, $updateValues)
    {
        DB::update($updateQuery, $updateValues);

    }

}
