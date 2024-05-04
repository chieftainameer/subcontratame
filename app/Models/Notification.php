<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = "notifications";
    
    protected $fillable = [
        'city_id',
        'image',
        'title',
        "subtitle",
        'type',
        'date_start',
        'date_end',
        'description',
        'image_firebase_uid',
        'status',
        'enterprise_id',
    ];

    public function notifyAllUsers() {
        $users = User::all();
        foreach($users as $user) {
            UserNotification::updateOrCreate([
                'notification_id' => $this->id,
                'user_id' => $user->id
            ],[
                'notification_id' => $this->id,
                'user_id' => $user->id,
                'visualized' => false
            ]);
        }
    }

    public function notifyAllUsersEnterprise() {
        $users = User::where('enterprise_id',auth()->user()->enterprise_id)->get();
        foreach($users as $user) {
            UserNotification::updateOrCreate([
                'notification_id' => $this->id,
                'user_id' => $user->id
            ],[
                'notification_id' => $this->id,
                'user_id' => $user->id,
                'visualized' => false
            ]);
        }
    }

    public function city() {
        return $this->belongsTo('App\Models\City');
    }
}
