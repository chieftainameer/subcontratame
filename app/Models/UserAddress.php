<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "users_address";
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'lat',
        'lng',
        'default'
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
