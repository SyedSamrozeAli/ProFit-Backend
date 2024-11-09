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
}
