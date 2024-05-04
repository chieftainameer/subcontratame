<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AutonomousCommunity extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='autonomous_community';
    protected $fillable=[
        'name',
        'country_id',
        'status'
    ];

    /**
     * Get all of the provinces for the AutonomousCommunity
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class,'country_id','id','pais');
    }
}
