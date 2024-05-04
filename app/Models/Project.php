<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Departure;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'user_id',
        'code',
        'title',
        'image',
        'short_description',
        'detailed_description',
        'province_id',
        'delivery_place',
        'start_date',
        'final_date',
        'status'
    ];

    protected $casts = [
        'final_date' => 'datetime'
    ];

    /**
     * The categories that belong to the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_project')
                    ->withTimestamps();
    }

    /**
     * The payment_methods that belong to the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function payment_methods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, 'payment_method_project')
                    ->withTimestamps();
    }

    /**
     * Get the user that owns the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the departures for the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departures(): HasMany
    {
        return $this->hasMany(Departure::class);
    }

    /**
     * Get the province associated with the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function province(): HasOne
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }
}
