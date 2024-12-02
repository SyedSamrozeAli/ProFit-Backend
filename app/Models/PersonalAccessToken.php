<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PersonalAccessToken extends Model
{
    use HasFactory;
    protected $table = "password_reset_tokens";
    protected $fillable = ['email', 'token', 'created_at'];

    protected $primaryKey = "email";
    public $timestamps = false;

    static public function getEmail($token)
    {
        return DB::select("SELECT email FROM password_reset_tokens WHERE token = ?", [$token])[0];
    }

    static public function deleteToken($token)
    {
        DB::delete("DELETE FROM password_reset_tokens WHERE token=?", [$token]);
    }
}
