<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'must_change_password',
        'phone',
        'address',
        'city',
        'postcode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function serviceReviews()
    {
        return $this->hasMany(ServiceReview::class);
    }
    public function returns()
    {
        return $this->hasMany(ProductReturn::class);
    }
    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }
    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isCustomer()
    {
        return $this->role === 'customer';
    }
}
