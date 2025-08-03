<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'balance',
        'unique_identifier',
        'admin',
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
        'password' => 'hashed',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function groupRole($groupId)
    {
        return $this->groups()->where('group_id', $groupId)->first()?->pivot->role;
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function totalPoints()
    {
        return $this->points()->sum('points');
    }

    public function sheets()
    {
        return $this->belongsToMany(Sheet::class)
                    ->withPivot('production', 'consumption', 'note','admin_id')
                    ->withTimestamps();
    }


    public function isAdmin(): bool
    {
        return $this->admin === 1;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->unique_identifier = self::generateUniqueCode();
        });
    }

    protected static function generateUniqueCode(): string
    {
        do {
            $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('unique_identifier', $code)->exists());

        return $code;
    }
}

