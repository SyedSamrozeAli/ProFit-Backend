<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'admin';

    protected $primaryKey = 'admin_id';
    protected $hidden = ['password'];
    protected $fillable = ['username', 'email', 'password'];

    public static function updatePassword($email, $password)
    {
        $password = bcrypt($password);
        DB::update("UPDATE admin SET password = ? WHERE email =?", [$password, $email]);
    }

}
