<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    use HasFactory,SoftDeletes;

    public $guarded = [];

    public function communities()
    {
        return $this->hasMany(AutonomousCommunity::class,'country_id','id')->orderBy('name','asc');
    }
}
