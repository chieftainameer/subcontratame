<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departure extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'project_id',
        'code',
        'description',
        'execution_date',
        'quantity',
        'dimensions',
        'dimension_id',
        'visible',
        'complete',
        'status'
    ];

    /**
     * Get the project that owns the Departure
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all of the variables for the Departure
     *
     * @return HasMany
     */
    public function variables(): HasMany
    {
        return $this->hasMany(Variable::class);
    }

    /**
     * Get all of the comments for the Departure
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all of the variants for the Departure
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class, 'departure_id');
    }

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }
}
