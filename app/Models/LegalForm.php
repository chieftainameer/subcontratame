<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalForm extends Model
{
    use HasFactory;
    protected $table='legal_forms';
    protected $fillable=[
        'name',
        'status'
    ];

    /**
     * Get the user that owns the LegalForm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'legal_form_id');
    }
}
