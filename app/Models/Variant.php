<?php

namespace App\Models;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Variant extends Model
{
    use HasFactory;
    protected $table='variants';
    protected $fillable=[
        'departure_id',
        'user_id',
        'type',
        'description',
        'includes',
        'quantity',
        'price_unit',
        'price_total',
        'iva',
        'simple_option',
        'multiple_option',
        'upload_information',
        'expiration_date',
        'percentage_iva'
    ];

    /**
     * Get the departure that owns the Variant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(Departure::class);
    }

    /**
     * The payment_methods that belong to the Variant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function payment_methods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, 'payment_method_variant')
                    ->withTimestamps();
    }

    /**
     * Get the user that owns the Variant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The roles that belong to the Variant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // public function roles(): BelongsToMany
    // {
    //     return $this->belongsToMany(Role::class, 'role_user_table', 'user_id', 'role_id');
    // }
}
