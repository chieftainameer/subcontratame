<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempNotificationDeparture extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'variant_id',
        'code',
        'type'
    ];
}
