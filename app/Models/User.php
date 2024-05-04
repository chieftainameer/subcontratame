<?php

namespace App\Models;


use App\Models\Comment;
use App\Models\PaymentMethod;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use App\Services\OneSignalService;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notifiable;
//use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'image',
        'first_name',
        'last_name',
        'cellphone',
        'company_name',
        'company_description',
        'province_id',
        'nif',
        'tax_residence',
        'email',
        'password',
        'legal_form_id',
        'last_access',
        'role',
        'status',
        'position',
        'i_agree',
        'token_complete',
        'token_password',
        'key_words',
        'hide_email',
        'hide_cellphone'
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'email_verified_at',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roleName()
    {
        switch($this->role) {
            case '1':  return 'Administrador'; break;
            case '2':  return 'Usuario'; break;
        }
    }

    public function isAdmin(){
        if($this->role==1){
            return true;
        }

        return false;
    }

    /**
     * Get the legal_form associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function legal_form(): HasOne
    {
        return $this->hasOne(LegalForm::class, 'legal_form_id', 'id');
    }

    
    /**
     * Get the province associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function province(): HasOne
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    /**
     * The category that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_user')
                    ->withTimestamps();
    }

    /**
     * The payment_methods that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function payment_methods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, 'payment_method_user')
                    ->withTimestamps();
    }

    /**
     * Get all of the projects for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all of the variants for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }


    /**
     * Get all of the ratings for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get all of the transactions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function transactions(): HasMany
    // {
    //     return $this->hasMany(Transaction::class);
    // }
    
    // public function sendNotification($title,$message) {
    //     OneSignalService::sendPushNotification($this->id,$title,$message);
    // }

    // public function payments() {
    //     return $this->hasMany(Payment::class);
    // }

    
}
