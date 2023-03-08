<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Traits\HasRoles;

class User extends Model
{
    use HasFactory;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'company',
        'company_id',
        'phone',
        'email',
        'password',
        'ref',
        'owner',
        'settings',
        'wallet',
        'notes',
        'temp_token',
        'forwarder_id',
        'forwarders',
        'membership',
        'membership_start',
        'membership_end',
        'api_key',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'last_login',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'wallet'            => 'json',
        'settings'          => 'json',
        'forwarders'        => 'json',
        'notes'             => 'json',
        'membership'        => 'string',
        'temp_token'        => 'string',
        'email_verified_at' => 'datetime',
        'membership_start' => 'datetime',
        'membership_end' => 'datetime',
    ];

    protected function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords($value),

            set: function($value){
                return strtolower($value);
            },
        );
    }

    public function ownerdata() : BelongsTo
    {
        return $this->belongsTo(User::class, 'owner', 'ref');
    }


    // Forwarders BelongsTo
    public function forwarder() : BelongsTo
    {
        return $this->BelongsTo(Forwarder::class, 'forwarder_id', 'id');
    }

    // Company BelongsTo
    public function companyinfo() : BelongsTo
    {
        return $this->BelongsTo(Company::class, 'company_id', 'id');
    }


    // Orders OneToMany

    public function orders_sold(){
        return $this->HasMany(Order::class, 'sales_id', 'id');
    }

    public function orders_bought(){
        return $this->HasMany(Order::class, 'client_id', 'id');
    }

    //Polymorphic

    public function archivo(){
        return $this->morphOne(Archivo::class, 'archivable');
    }
}
