<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'membership';
    protected $primaryKey = 'memberhip_id';
    // Create a new membership record
    public static function createMembership($data)
    {
        return DB::insert("INSERT INTO memberships (membership_type) VALUES (?)", [$data['membership_type']]);
    }

    // Read a single membership record
    public static function getMembershipById($membershipId)
    {
        return DB::select("SELECT * FROM memberships WHERE membership_id = ?", [$membershipId]);
    }

    // Read all membership records
    public static function getAllMemberships()
    {
        return DB::select("SELECT * FROM memberships");
    }

    // Update a membership record
    public static function membershipUpdate($membershipId, $data)
    {
        return DB::update("UPDATE memberships SET membership_type = ? WHERE membership_id = ?", [
            $data['membership_type'],
            $membershipId,
        ]);
    }

    // Delete a membership record
    public static function deleteMembership($membershipId)
    {
        return DB::delete("DELETE FROM memberships WHERE membership_id = ?", [$membershipId]);
    }

    static public function getMembershipID($membershipType)
    {
        return DB::select("SELECT membership_id FROM memberships WHERE membership_type = ?", [$membershipType]);
    }

    static public function addMemberIntoMembership($memberId, $membershipId, $price, $membershipDuration)
    {
        // Linking members with their memberships
        DB::insert("INSERT INTO member_has_membership (member_id,membership_id, price, duration, start_date, end_date, status)
                VALUES(?,?,?,?,?,?,?)
            ", [
            $memberId,
            $membershipId,
            $price,
            (string) $membershipDuration,
            now(),
            now()->addDays($membershipDuration * 30),
            'active'
        ]);
    }

    static public function updateMembership($memberId, $price, $duration, $start_date, $end_date, $membershipId)
    {
        DB::update("UPDATE member_has_membership SET membership_id = ?, price=? , duration=? ,start_date=?, end_date=? WHERE member_id = ?", [$membershipId, $price, $duration, $start_date, $end_date, $memberId]);
    }

    static public function getMemberMembership($memberId)
    {
        return DB::select("SELECT membership_id FROM member_has_membership WHERE member_id = ?", [$memberId]);
    }

    public static function getMembershipTypeData()
    {
        return DB::select(
            "SELECT 
                M.membership_type, 
                COUNT(*) as total_members 
            FROM member_has_membership MM
            JOIN memberships M 
            ON MM.membership_id = M.membership_id
            GROUP BY membership_type
    "
        );
    }

}
