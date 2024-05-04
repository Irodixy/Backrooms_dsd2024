<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type', 
        'bday', 
        'IDInterests',
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

    // Relações
    public function interests()
    {
        return $this->belongsTo(Interests::class, 'IDInterests', 'ID');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'IDUser', 'ID');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'IDUser', 'ID');
    }

    public function huntedStores()
    {
        return $this->hasMany(HuntedStore::class, 'IDUser', 'ID');
    }

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
        ];
    }
}
