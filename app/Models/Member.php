<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $primaryKey = 'member_id';

    protected $fillable = [
        'name',
        'member_email',
        'phone_number',
        'address',
        'age',
        'CNIC',
        'DOB',
        'trainer_id',
        'height',
        'weight',
        'bmi',
        'membership_type',
        'profile_image',
        'health_issues',
        'user_status',
        'addmission_date',
        'membership_start_date',
        'membership_end_date'

    ];

        protected $casts = [
      
        'addmission_date' => 'datetime:Y-m-d H:m:s',
        'membership_start_date' => 'datetime:Y-m-d H:m:s',
        'membership_end_date' => 'datetime:Y-m-d H:m:s',
    ];
}
