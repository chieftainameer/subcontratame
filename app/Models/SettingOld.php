<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $fillable = [
        'site_name',
        'email',
        'phone',
        'address',
        'copyright',
        'privacity',
        'conditions',
        'message_sharing',
        'onesignal_id',
        'onesignal_token',
        'logo',
        'urgency_description',
        'emergency_description',
        # Firebase
        'firebase_status',
        'firebase_firestore',
        'firebase_bucket',
        'firebase_credentials',
        # GoogleMaps
        'googlemaps_key',
        'googlemaps_libraries',
        # Payments
        'price_per_code',
    ];
}
