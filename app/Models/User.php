<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
<<<<<<< HEAD
        'points',
=======
>>>>>>> 3497b1b0449ac3de36406bec14c395d270bd056c
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Hubungan ke tabel categories
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    // Hubungan ke tabel expenses
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    // Hubungan ke tabel challenges
    public function challenges(): HasMany
    {
        return $this->hasMany(Challenge::class);
    }

    // Hubungan ke tabel user_rewards
    public function userRewards(): HasMany
    {
        return $this->hasMany(UserReward::class);
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 3497b1b0449ac3de36406bec14c395d270bd056c
