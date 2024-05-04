<?php

namespace App\Models;

use App\Models\User;
use App\Models\Project;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'description',
        'status'
    ];

    /**
     * The user that belong to the PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'payment_method_user')
                    ->withTimestamps();
    }

    /**
     * The projects that belong to the PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'payment_method_project')
                    ->withTimestamps();
    }

    /**
     * The variants that belong to the PaymentMethod
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class, 'payment_method_variant')
                    ->withTimestamps();
    }
}
