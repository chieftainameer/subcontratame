<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminUser extends Model
{
    use HasFactory, Authenticatable;

    protected $table = "users";

    protected $fillable = [
        "firebase_uid",
        'city_id',
        'enterprise_id',
        'lang',
        'image',
        'image_firebase_uid',
        'name',
        'last_name',
        'birthdate',
        'blood_type',
        'typeinsurance_id',
        'dni',
        'phone',
        'address',
        'lat',
        'lng',
        'cp',
        'user_id',
        'medical_license',
        'signature',
        'signature_firebase_uid',
        'email',
        'password',
        'role',
        'otp',
        'status',
    ];
}
