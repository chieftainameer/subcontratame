<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table='settings';
    protected $fillable=[
        'terms_conditions',
        'privacy_policies',
        'contact_cellphone',
        'contact_email',
        'contact_linkedin',
        'price_departure',
        'price_variable',
        'stripe_public_key',
        'stripe_secret_key',
        'percentage_iva',
        'about'
    ];
}
