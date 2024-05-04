<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Variable extends Model
{
    use HasFactory;
    protected $fillable=[
        'departure_id',
        'type',
        'description',
        'options',
        'required',
        'visible',
        'text',
        'file',
        'download_file',
        'status'
    ];

    /**
     * Get the departure that owns the Variable
     *
     * @return BelongsTo
     */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }
}
