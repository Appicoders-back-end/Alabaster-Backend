<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const Admin = 'admin';
    public const Contractor = 'contractor';
    public const Cleaner = 'cleaner';
    public const Customer = 'customer';
    public const Active = 'active';
    public const InActive = 'inactive';
    public const Delete = 'delete';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'role',
        'password',
        'contact_no',
        'profile_image',
        'is_online',
        'stripe_customer_id',
        'device_id',
        'status',
        'category_id',
        'working_start_time',
        'working_end_time',
        'break_time_in',
        'break_time_out',
        'is_receive_notification',
        'get_started',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    /* employee/cleaner categories */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_users', 'user_id', 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function membership()
    {
        return $this->hasOne(UserSubscription::class, 'user_id', 'id');
    }

    public function hasMembership()
    {
        if ($this->membership()->where('is_expired', 0)->count() == 0) {
            return false;
        }
        return true;
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function contractorCompanies()
    {
        return $this->hasMany(Company::class, 'contractor_id', 'id');
    }
}
