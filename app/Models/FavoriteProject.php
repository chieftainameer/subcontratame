<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteProject extends Model
{
    use HasFactory;

    protected $fillable=[
        'project_id',
        'user_id',
        'image',
        'code',
        'title',
        'location',
        'expiration_date'
    ];
}
