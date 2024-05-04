<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;
    protected $table='document_user';
    protected $fillable = [
        'user_id',
        'path',
        'document_id',
        'description',
        'status',
    ];
    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function document() {
        return $this->belongsTo(\App\Models\Document::class);
    }
}
